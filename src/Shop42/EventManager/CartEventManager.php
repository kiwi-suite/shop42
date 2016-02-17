<?php
namespace Shop42\EventManager;

use Zend\EventManager\EventManager;

class CartEventManager extends EventManager
{
    const EVENT_PREPARE_NEW = 'prepare_new';
    const EVENT_FINISH_NEW = 'finish_new';
    const EVENT_PREPARE_EXISTENT = 'prepare_existent';
    const EVENT_FINISH_EXISTENT = 'finish_existent';
}
