<?php

namespace Shop42\Billing;

interface TotalCalculableInterface {

    /**
     * @return int
     */
    public function getTotalQuantity();

    /**
     * @return float
     */
    public function getTotalPriceBeforeTax();

    /**
     * @return float
     */
    public function getTotalPriceAfterTax();

    /**
     * @return float
     */
    public function getTotalTaxPrice();

}