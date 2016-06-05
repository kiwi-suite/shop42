<?php

namespace Shop42\Billing;

class Bill implements BillableInterface, \Iterator, \Countable, \JsonSerializable
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var string
     */
    protected $handle = "";

    /**
     * @var string
     */
    protected $currency;

    /**
     * Bill constructor.
     * @param string $handle
     */
    public function __construct($handle = "")
    {
        if (!empty($handle)) {
            $this->setHandle($handle);
        }
    }

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
     * @return string
     * @throws \Exception
     */
    public function getCurrency()
    {
        if (!empty($this->currency)) {
            return $this->currency;
        }

        $currency = [];

        $this->deepItemWalk($this->getItems(), function (ItemInterface $item) use (&$currency) {
            $currency[$item->getCurrency()] = true;
        });

        if (count($currency) == 0) {
            return null;
        }

        if (count($currency) > 1) {
            throw new \Exception("currency mismatch");
        }

        $this->currency = array_keys($currency)[0];
        return $this->currency;
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
        return array_sum(array_map(function (BillableInterface $item) {
            return $item->getTotalPriceBeforeTax();
        }, $items));
    }

    /**
     * @return float
     */
    protected function sumPriceAfterTax($items)
    {
        return array_sum(array_map(function (BillableInterface $item) {
            return $item->getTotalPriceAfterTax();
        }, $items));
    }

    /**
     * @return float
     */
    protected function sumTaxPrice($items)
    {
        return array_sum(array_map(function (BillableInterface $item) {
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

        $this->deepItemWalk($items, function (ItemInterface $item) use (&$taxes) {
            if (! array_key_exists($item->getTax(), $taxes)) {
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
        return array_sum(array_map(function (BillableInterface $item) {
            return $item->getTotalQuantity();
        }, $items));
    }

    /**
     *
     */
    public function rewind()
    {
        reset($this->items);
    }

    /**
     * @return ItemInterface
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * @return int
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * @return ItemInterface
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return key($this->items) !== null;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $items = [];
        foreach ($this->getItems() as $item) {
            $items[] = $item->toArray();
        }

        return [
            'totalPriceAfterTax' => $this->getTotalPriceAfterTax(),
            'totalPriceBeforeTax' => $this->getTotalPriceBeforeTax(),
            'totalTaxGrouped' => $this->getTotalTaxGrouped(),
            'totalQuantity' => $this->getTotalQuantity(),
            'totalTaxPrice' => $this->getTotalTaxPrice(),
            'currency' => $this->getCurrency(),
            'items' => $items,
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @param $handle
     * @return $this
     */
    public function setHandle($handle)
    {
        $this->handle = $handle;

        return $this;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @param $handle
     * @return BillableInterface|null
     */
    public function getItemByHandle($handle)
    {
        foreach ($this->getItems() as $item) {
            if ($item->getHandle() == $handle) {
                return $item;
            }
        }
    }
}
