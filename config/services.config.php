<?php
namespace Shop42;

use Shop42\EventListener\CartEventListener;
use Shop42\EventListener\CheckoutEventListener;
use Shop42\EventListener\Service\CartEventListenerFactory;
use Shop42\EventListener\Service\CheckoutEventListenerFactory;
use Shop42\EventManager\CartEventManager;
use Shop42\EventManager\CheckoutEventManager;
use Shop42\Ixopay\Ixopay;
use Shop42\Ixopay\Service\IxopayFactory;
use Shop42\NumberGenerator\NextOrder;
use Shop42\NumberGenerator\NextOrderInterface;
use Shop42\NumberGenerator\Service\NextOrderFactory;
use Shop42\TableGateway\CartTableGatewayInterface;
use Shop42\TableGateway\OrderTableGatewayInterface;
use Shop42\TableGateway\ProductI18nTableGatewayInterface;
use Shop42\TableGateway\ProductTableGatewayInterface;
use Shop42\TableGateway\Service\TableGatewayFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'factories' => [
            CartEventManager::class => InvokableFactory::class,
            CartEventListener::class => CartEventListenerFactory::class,

            CheckoutEventManager::class => InvokableFactory::class,
            CheckoutEventListener::class => CheckoutEventListenerFactory::class,

            Ixopay::class => IxopayFactory::class,

            NextOrder::class => NextOrderFactory::class,
        ],
        'aliases' => [
            NextOrderInterface::class => NextOrder::class,
        ],
    ],
    'table_gateway' => [
        'factories' => [
            CartTableGatewayInterface::class => TableGatewayFactory::class,
            OrderTableGatewayInterface::class => TableGatewayFactory::class,
            ProductI18nTableGatewayInterface::class => TableGatewayFactory::class,
            ProductTableGatewayInterface::class => TableGatewayFactory::class,
        ],
    ],
];
