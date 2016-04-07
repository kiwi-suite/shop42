<?php
namespace Shop42\EventListener\Service;

use Interop\Container\ContainerInterface;
use Shop42\Command\Stock\ChangeCommand;
use Shop42\EventListener\CheckoutEventListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CheckoutEventListenerFactory implements FactoryInterface
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
        return new CheckoutEventListener(
            $container->get('Command')->get(ChangeCommand::class)
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
        return $this($serviceLocator, CheckoutEventListener::class);
    }
}
