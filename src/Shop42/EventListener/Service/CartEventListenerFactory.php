<?php
namespace Shop42\EventListener\Service;

use Interop\Container\ContainerInterface;
use Shop42\Command\Cart\CleanupCommand;
use Shop42\EventListener\CartEventListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CartEventListenerFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new CartEventListener(
            $container->get('Command')->get(CleanupCommand::class)
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null)
    {
        return $this($serviceLocator, CartEventListener::class);
    }
}
