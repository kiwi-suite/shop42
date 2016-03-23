<?php

namespace Shop42\Billing;

class Container implements BillableInterface, \Iterator, \Countable {
    
    protected $items = [];

    /**
     * @param BillableInterface $item
     * @return $this
     */
    public function addItem(BillableInterface $item)
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
        return array_sum(array_map(function(BillableInterface $item){
            return $item->getTotalPriceBeforeTax();
        }, $this->getItems()));
    }

    /**
     * @return float
     */
    public function getTotalPriceAfterTax()
    {
        return array_sum(array_map(function(BillableInterface $item){
            return $item->getTotalPriceAfterTax();
        }, $this->getItems()));
    }

    /**
     * @return int
     */
    public function getTotalQuantity()
    {
        return array_sum(array_map(function(BillableInterface $item){
            return $item->getTotalQuantity();
        }, $this->getItems()));
    }

    /**
     * @return float
     */
    public function getTotalTaxPrice()
    {
        return array_sum(array_map(function(BillableInterface $item){
            return $item->getTotalTaxPrice();
        }, $this->getItems()));
    }

    /**
     * @return float
     */
    public function getSubTotalPriceBeforeTax()
    {
        return array_sum(array_map(function(BillableInterface $bill){
            return $bill->getTotalPriceBeforeTax();
        }, array_slice($this->items, 0, $this->key()+1)));
    }

    /**
     * @return float
     */
    public function getSubTotalPriceAfterTax()
    {
        return array_sum(array_map(function(BillableInterface $bill){
            return $bill->getTotalPriceAfterTax();
        }, array_slice($this->items, 0, $this->key()+1)));
    }

    /**
     * @return int
     */
    public function getSubTotalQuantity()
    {
        return array_sum(array_map(function(BillableInterface $item){
            return $item->getTotalQuantity();
        }, array_slice($this->items, 0, $this->key()+1)));
    }

    /**
     * @return float
     */
    public function getSubTotalTaxPrice()
    {
        return array_sum(array_map(function(BillableInterface $item){
            return $item->getTotalTaxPrice();
        }, array_slice($this->items, 0, $this->key()+1)));
    }

    public function rewind()
    {
        reset($this->items);
    }

    public function current()
    {
        return current($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function next()
    {
        return next($this->items);
    }

    public function valid()
    {
        return key($this->items) !== null;
    }

    public function count()
    {
        return count($this->items);
    }
    
}