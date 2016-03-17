<?php
namespace Shop42\Model;

use Core42\Model\ModelInterface;
use Shop42\Billing\ItemInterface;

interface OrderItemInterface extends ModelInterface, ItemInterface
{
    /**
     * @param mixed $orderId
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * @return mixed
     */
    public function getOrderId();

    /**
     * @param string $currency
     * @return mixed
     */
    public function setCurrency($currency);

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * @param bool $specialItem
     * @return $this
     */
    public function setSpecialItem($specialItem);

    /**
     * @return bool
     */
    public function getSpecialItem();
}
