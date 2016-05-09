<?php
namespace Shop42\EventManager;

use Zend\EventManager\EventManager;

class OrderEventManager extends EventManager
{
    const EVENT_DELIVERY = 'delivery';
}
