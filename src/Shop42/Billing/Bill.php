<?php

namespace Shop42\Billing;

class Bill extends Container{

    /**
     * @return array
     */
    public function getTotalTaxGrouped()
    {
        $taxes = [];

        $appendTaxes = function(ItemInterface $item) use (&$taxes){
            if ( ! array_key_exists($item->getTax(), $taxes)) {
                $taxes[$item->getTax()] = $item->getTotalTaxPrice();
            } else {
                $taxes[$item->getTax()] += $item->getTotalTaxPrice();
            }
        };

        foreach ($this->items as $item) {

            if ($item instanceof ItemInterface) {
                /** @var ItemInterface $item */
                $appendTaxes($item);
            } elseif ($item instanceof Container) {
                /** @var Container $item */
                foreach ($item->getItems() as $subItem) {
                    $appendTaxes($subItem);
                }
            }
        }

        ksort($taxes);

        return $taxes;
    }

}