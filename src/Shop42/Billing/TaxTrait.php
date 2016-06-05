<?php

namespace Shop42\Billing;

trait TaxTrait
{
    /**
     * @return float
     */
    abstract public function getPrice();

    /**
     * @return int
     */
    abstract public function getTax();

    /**
     * @return boolean
     */
    abstract public function getTaxIncluded();

    /**
     * @return int
     */
    abstract public function getQuantity();

    /**
     * @return float
     */
    public function getSinglePriceBeforeTax()
    {
        if ($this->getTaxIncluded()) {
            $price = $this->getPrice() - $this->getSingleTaxPrice();
        } else {
            $price = $this->getPrice();
        }

        return round($price, 2, PHP_ROUND_HALF_UP);
    }

    /**
     * @return float
     */
    public function getTotalPriceBeforeTax()
    {
        return $this->getSinglePriceBeforeTax() * $this->getTotalQuantity();
    }

    /**
     * @return float
     */
    public function getSinglePriceAfterTax()
    {
        if ($this->getTaxIncluded()) {
            $price = $this->getPrice();
        } else {
            $price = $this->getPrice() + $this->getSingleTaxPrice();
        }

        return round($price, 2, PHP_ROUND_HALF_UP);
    }

    /**
     * @return float
     */
    public function getTotalPriceAfterTax()
    {
        return $this->getSinglePriceAfterTax() * $this->getTotalQuantity();
    }

    /**
     * @return float
     */
    public function getSingleTaxPrice()
    {
        if ($this->getTaxIncluded()) {
            $price = $this->getPrice() - $this->getPrice() / (1 + $this->getTax() / 100);
        } else {
            $price = $this->getPrice() * $this->getTax() / 100;
        }

        return round($price, 2, PHP_ROUND_HALF_DOWN);
    }

    /**
     * @return float
     */
    public function getTotalTaxPrice()
    {
        return $this->getSingleTaxPrice() * $this->getTotalQuantity();
    }
}
