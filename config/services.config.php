<?php
namespace Shop42;

use Shop42\EventListener\CartEventListener;
use Shop42\EventListener\CheckoutEventListener;
use Shop42\EventListener\Service\CartEventListenerFactory;
use Shop42\EventListener\Service\CheckoutEventListenerFactory;
use Shop42\EventManager\CartEventManager;
use Shop42\EventManager\CheckoutEventManager;
use Shop42\Model\CartInterface;
use Shop42\Model\OrderInterface;
use Shop42\Model\ProductI18nInterface;
use Shop42\Model\ProductInterface;
use Shop42\TableGateway\Service\TableGatewayFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'service_manager' => [
        'factories' => [
            CartEventManager::class => InvokableFactory::class,
            CartEventListener::class => CartEventListenerFactory::class,

            CheckoutEventManager::class => InvokableFactory::class,
            CheckoutEventListener::class => CheckoutEventListenerFactory::class,
        ],
    ],
    'table_gateway' => [
        'factories' => [
            CartInterface::class => TableGatewayFactory::class,
            OrderInterface::class => TableGatewayFactory::class,
            ProductI18nInterface::class => TableGatewayFactory::class,
            ProductInterface::class => TableGatewayFactory::class,
        ],
    ],
];
