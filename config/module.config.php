<?php
namespace Shop42;
use Shop42\TableGateway\CartTableGatewayInterface;
use Shop42\TableGateway\OrderTableGatewayInterface;
use Shop42\TableGateway\ProductI18nTableGatewayInterface;
use Shop42\TableGateway\ProductTableGatewayInterface;

return [
    'shop42' => [
        'table_gateway' => [
            CartTableGatewayInterface::class => '',
            OrderTableGatewayInterface::class => '',
            ProductI18nTableGatewayInterface::class => '',
            ProductTableGatewayInterface::class => '',
        ],
    ],
];
