<?php

namespace CanalTP\NmmPortalBundle\Features\Context;

use Behat\MinkExtension\Context\MinkContext;
use PSS\Behat\Symfony2MockerExtension\ServiceMocker;
use Behat\Symfony2Extension\Context\KernelDictionary;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext
{
    use KernelDictionary;

    /**
     * @var ServiceMocker
     */
    private $serviceMocker;

    /**
     * @param ServiceMocker $serviceMocker
     */
    public function __construct(ServiceMocker $serviceMocker)
    {
        $this->serviceMocker = $serviceMocker;
    }

    public function setKernel($kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^(?:|I )am logged in as Administrator$/
     */
    public function iAmLoggedInAsAdministrator()
    {
        $this->visit('/admin/login');
        $this->fillField('username', 'admin');
        $this->fillField('password', 'admin');
        $this->pressButton('Connexion');

        $this->serviceMocker->mockService('canal_tp_tyr.api', 'CanalTP\TyrComponent\Guzzle3\TyrService')
            ->shouldReceive('getUsers')
            ->once()
            ->andReturn("Hello World !")
        ;
        $container = $this->getContainer();
        $users = $container->get('canal_tp_tyr.api')->getUsers();

        dump($users);
    }
}
