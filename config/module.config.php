<?php
namespace Shop42;
use Shop42\Model\CartInterface;
use Shop42\Model\OrderInterface;
use Shop42\Model\OrderItemInterface;
use Shop42\Model\ProductI18nInterface;
use Shop42\Model\ProductInterface;

return [
    'shop42' => [
        'table_gateway' => [
            CartInterface::class => '',
            OrderInterface::class => '',
            OrderItemInterface::class => '',
            ProductI18nInterface::class => '',
            ProductInterface::class => '',
        ],
    ],
];
