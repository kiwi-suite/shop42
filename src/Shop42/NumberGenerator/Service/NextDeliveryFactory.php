<?php
namespace Shop42\NumberGenerator\Service;

use Interop\Container\ContainerInterface;
use Shop42\NumberGenerator\NextDelivery;
use Shop42\TableGateway\OrderTableGatewayInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NextDeliveryFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return NextDelivery
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new NextDelivery(
            $container->get('TableGateway')->get(OrderTableGatewayInterface::class)
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return NextDelivery
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, NextDelivery::class);
    }
}
