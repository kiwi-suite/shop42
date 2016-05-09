<?php

namespace Shop42\Billing;

interface BillableInterface {

    /**
     * @return string
     */
    public function getHandle();

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

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function getCurrency();

}
