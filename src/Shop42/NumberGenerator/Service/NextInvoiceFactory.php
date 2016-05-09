<?php
namespace Shop42\NumberGenerator\Service;

use Interop\Container\ContainerInterface;
use Shop42\NumberGenerator\NextInvoice;
use Shop42\TableGateway\OrderTableGatewayInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NextInvoiceFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return NextInvoice
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new NextInvoice(
            $container->get('TableGateway')->get(OrderTableGatewayInterface::class)
        );
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return NextInvoice
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, NextInvoice::class);
    }
}
