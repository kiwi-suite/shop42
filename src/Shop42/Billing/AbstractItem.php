<?php

namespace Shop42\Billing;

abstract class AbstractItem implements ItemInterface {

    use TaxTrait;

    protected $price;

    protected $tax;

    protected $taxIncluded;

    protected $quantity;

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param int $tax
     * @return $this
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getTaxIncluded()
    {
        return $this->taxIncluded;
    }

    /**
     * @param boolean $taxIncluded
     * @return $this
     */
    public function setTaxIncluded($taxIncluded)
    {
        $this->taxIncluded = $taxIncluded;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

}