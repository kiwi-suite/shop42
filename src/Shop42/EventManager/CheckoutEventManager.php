<?php
namespace Shop42\EventManager;

use Zend\EventManager\EventManager;

class CheckoutEventManager extends EventManager
{
    const EVENT_CHECKOUT_PRE = 'checkout.pre';
    const EVENT_CHECKOUT_ERROR = 'checkout.error';
    const EVENT_CHECKOUT_POST = 'checkout.post';

    const EVENT_CALLBACK_PRE = 'callback.pre';
    const EVENT_CALLBACK_POST = 'callback.post';
    const EVENT_CALLBACK_SUCCESS = 'callback.success';
    const EVENT_CALLBACK_ERROR = 'callback.error';
    const EVENT_CALLBACK_PENDING = 'callback.pending';
    const EVENT_CALLBACK_FAIL = 'callback.fail';
}
