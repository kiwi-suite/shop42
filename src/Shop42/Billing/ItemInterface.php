<?php

namespace Shop42\Billing;

interface ItemInterface extends BillableInterface {

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
     * @return $this
     */
    public function setTax($tax);

    /**
     * @return int
     */
    public function getTax();

    /**
     * @param boolean
     * @return $this
     */
    public function setTaxIncluded($taxIncluded);

    /**
     * @return boolean
     */
    public function getTaxIncluded();

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity);

    /**
     * @return float
     */
    public function getSinglePriceBeforeTax();

    /**
     * @return float
     */
    public function getSinglePriceAfterTax();

    /**
     * @return float
     */
    public function getSingleTaxPrice();

}