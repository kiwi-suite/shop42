<?php
namespace Shop42\Model;

use Core42\Model\ModelInterface;

interface OrderItemInterface extends ModelInterface
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
     * @param mixed $productId
     * @return $this
     */
    public function setProductId($productId);

    /**
     * @return mixed
     */
    public function getProductId();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @param int $tax
     * @return mixed
     */
    public function setTax($tax);

    /**
     * @return int
     */
    public function getTax();

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
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity);

    /**
     * @return int
     */
    public function getQuantity();

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
