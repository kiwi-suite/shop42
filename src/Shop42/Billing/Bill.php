<?php

namespace Shop42\Billing;

class Bill {

    protected $items = [];

    /**
     * @param ItemInterface $item
     * @return $this
     */
    public function addItem(ItemInterface $item)
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @param array $items
     * @return $this
     */
    public function setItems(array $items = [])
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return float
     */
    public function getTotalPriceBeforeTax()
    {
        return array_sum(array_map(function(ItemInterface $item){
            return $item->getTotalPriceBeforeTax();
        }, $this->items));
    }

    /**
     * @return float
     */
    public function getTotalPriceAfterTax()
    {
        return array_sum(array_map(function(ItemInterface $item){
            return $item->getTotalPriceAfterTax();
        }, $this->items));
    }

    /**
     * @return int
     */
    public function getTotalItems()
    {
        return array_sum(array_map(function(ItemInterface $item){
            return $item->getQuantity();
        }, $this->items));
    }

    /**
     * @return float
     */
    public function getTotalTax()
    {
        return array_sum(array_map(function(ItemInterface $item){
            return $item->getTotalTaxPrice();
        }, $this->items));
    }

    /**
     * @return array
     */
    public function getTotalTaxGrouped()
    {
        $taxes = [];

        /** @var ItemInterface $item */
        foreach ($this->items as $item) {
            if ( ! array_key_exists($item->getTax(), $taxes)) {
                $taxes[$item->getTax()] = $item->getTotalTaxPrice();
            } else {
                $taxes[$item->getTax()] += $item->getTotalTaxPrice();
            }
        }

        ksort($taxes);

        return $taxes;
    }

}