<?php

namespace Shop42\Billing;

class Bill implements BillableInterface, \Iterator, \Countable {

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
        return $this->sumPriceBeforeTax($this->getItems());
    }

    /**
     * @return float
     */
    public function getTotalPriceAfterTax()
    {
        return $this->sumPriceAfterTax($this->getItems());
    }

    /**
     * @return float
     */
    public function getTotalTaxPrice()
    {
        return $this->sumTaxPrice($this->getItems());
    }

    /**
     * @return array
     */
    public function getTotalTaxGrouped()
    {
        return $this->groupTaxes($this->getItems());
    }

    /**
     * @return int
     */
    public function getTotalQuantity()
    {
        return $this->sumQuantity($this->getItems());
    }

    /**
     * @return float
     */
    public function getSubTotalPriceBeforeTax()
    {
        return $this->sumPriceBeforeTax(array_slice($this->items, 0, $this->key()+1));
    }

    /**
     * @return float
     */
    public function getSubTotalPriceAfterTax()
    {
        return $this->sumPriceAfterTax(array_slice($this->items, 0, $this->key()+1));
    }

    /**
     * @return float
     */
    public function getSubTotalTaxPrice()
    {
        return $this->sumTaxPrice(array_slice($this->items, 0, $this->key()+1));
    }

    /**
     * @return float
     */
    public function getSubTotalTaxGrouped()
    {
        return $this->groupTaxes(array_slice($this->items, 0, $this->key()+1));
    }

    /**
     * @return int
     */
    public function getSubTotalQuantity()
    {
        return $this->sumQuantity(array_slice($this->items, 0, $this->key()+1));
    }

    /**
     * @param array $items
     * @param \Closure $callback
     */
    protected function deepItemWalk($items, $callback)
    {
        foreach ($items as $item) {

            if ($item instanceof ItemInterface) {
                /** @var ItemInterface $item */
                $callback($item);
            } elseif ($item instanceof Bill) {
                /** @var Bill $item */
                $this->deepItemWalk($item->getItems(), $callback);
            }
        }
    }
    
    /**
     * @return float
     */
    protected function sumPriceBeforeTax($items)
    {
        return array_sum(array_map(function(BillableInterface $item){
            return $item->getTotalPriceBeforeTax();
        }, $items));
    }

    /**
     * @return float
     */
    protected function sumPriceAfterTax($items)
    {
        return array_sum(array_map(function(BillableInterface $item){
            return $item->getTotalPriceAfterTax();
        }, $items));
    }

    /**
     * @return float
     */
    protected function sumTaxPrice($items)
    {
        return array_sum(array_map(function(BillableInterface $item){
            return $item->getTotalTaxPrice();
        }, $items));
    }

    /**
     * @param array $items
     * @return array
     */
    protected function groupTaxes($items)
    {
        $taxes = [];

        $this->deepItemWalk($items, function(ItemInterface $item) use (&$taxes){
            if ( ! array_key_exists($item->getTax(), $taxes)) {
                $taxes[$item->getTax()] = $item->getTotalTaxPrice();
            } else {
                $taxes[$item->getTax()] += $item->getTotalTaxPrice();
            }
        });

        ksort($taxes);

        return $taxes;
    }

    /**
     * @return int
     */
    protected function sumQuantity($items)
    {
        return array_sum(array_map(function(BillableInterface $item){
            return $item->getTotalQuantity();
        }, $items));
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