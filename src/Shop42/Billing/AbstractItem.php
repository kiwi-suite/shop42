<?php

namespace Shop42\Billing;

abstract class AbstractItem implements ItemInterface {

    use TaxTrait;

    /**
     * @var float
     */
    protected $price;

    /**
     * @var int
     */
    protected $tax;

    /**
     * @var boolean
     */
    protected $taxIncluded;

    /**
     * @var int
     */
    protected $quantity;

    /**
     * @var int
     */
    protected $productId;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $uuid;

    /**
     * @var string
     */
    protected $currency;

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
     * @param mixed $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param string $uuid
     * @return $this
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }


    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'productId' => $this->getProductId(),
            'name' => $this->getName(),
            'uuid' => $this->getUuid(),
            'price' => $this->getPrice(),
            'quantity' => $this->getQuantity(),
            'tax' => $this->getTax(),
            'currency' => $this->getCurrency(),
            'taxIncluded' => $this->getTaxIncluded(),
            'totalQuantity' => $this->getTotalQuantity(),
            'totalTaxPrice' => $this->getTotalTaxPrice(),
            'totalPriceAfterTax' => $this->getTotalPriceAfterTax(),
            'totalPriceBeforeTax' => $this->getTotalPriceBeforeTax(),
            'singlePriceAfterTax' => $this->getSinglePriceAfterTax(),
            'singlePriceBeforeTax' => $this->getSinglePriceBeforeTax(),
            'singleTaxPrice' => $this->getSingleTaxPrice(),
        ];
    }

}
