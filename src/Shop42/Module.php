<?php
namespace Shop42;

use Shop42\EventListener\CartEventListener;
use Shop42\EventListener\CheckoutEventListener;
use Shop42\EventManager\CartEventManager;
use Shop42\EventManager\CheckoutEventManager;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements
    ConfigProviderInterface,
    BootstrapListenerInterface
{

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return array_merge(
            require_once __DIR__ . '/../../config/module.config.php',
            require_once __DIR__ . '/../../config/services.config.php',
            require_once __DIR__ . '/../../config/payment.config.php'
        );
    }

    /**
     * Listen to the bootstrap event
     *
     * @param EventInterface $e
     * @return array
     */
    public function onBootstrap(EventInterface $e)
    {
        $e->getApplication()
            ->getServiceManager()
            ->get(CartEventListener::class)
            ->attach(
                $e->getApplication()->getServiceManager()->get(CartEventManager::class)
            );

        $e->getApplication()
            ->getServiceManager()
            ->get(CheckoutEventListener::class)
            ->attach(
                $e->getApplication()->getServiceManager()->get(CheckoutEventManager::class)
            );
        
    }
}
