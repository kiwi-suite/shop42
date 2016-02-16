<?php
namespace Shop42\EventManager;

use Zend\EventManager\EventManager;

class CartEventManager extends EventManager
{
    const EVENT_RELATIVE_NEW_PRE = 'relative.new.pre';
    const EVENT_RELATIVE_NEW_POST = 'relative.new.post';
    const EVENT_RELATIVE_UPDATE_PRE = 'relative.update.pre';
    const EVENT_RELATIVE_UPDATE_POST = 'relative.update.post';
}
