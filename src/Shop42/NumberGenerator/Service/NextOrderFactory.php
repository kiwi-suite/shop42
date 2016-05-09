<?php
namespace Shop42\NumberGenerator\Service;

use Interop\Container\ContainerInterface;
use Shop42\NumberGenerator\NextOrder;
use Shop42\TableGateway\OrderTableGatewayInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NextOrderFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return NextOrder
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new NextOrder(
            $container->get('TableGateway')->get(OrderTableGatewayInterface::class)
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return NextOrder
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, NextOrder::class);
    }
}
