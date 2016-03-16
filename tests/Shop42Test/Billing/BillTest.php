<?php

namespace Shop42Test\Billing;

use Shop42\Billing\Bill;
use Shop42\Billing\ItemInterface;
use Shop42\Billing\AbstractItem;

class BillTest extends \PHPUnit_Framework_TestCase {

    /**
     * @param float $price
     * @param int $tax
     * @param int $quantity
     * @param boolean $taxIncluded
     * @return ItemInterface
     */
    protected function getItemMock($price, $tax, $quantity, $taxIncluded)
    {
        /** @var AbstractItem $itemMock */
        $itemMock = $this->getMockForAbstractClass(AbstractItem::class);

        $itemMock->setPrice($price);
        $itemMock->setTax($tax);
        $itemMock->setQuantity($quantity);
        $itemMock->setTaxIncluded($taxIncluded);

        return $itemMock;
    }

    public function testCalculations()
    {
        $bill = new Bill();

        $bill->addItem($this->getItemMock(100, 20, 5, false));
        $bill->addItem($this->getItemMock(50, 10, 3, false));

        $this->assertEquals(650, $bill->getTotalPriceBeforeTax());
        $this->assertEquals(765, $bill->getTotalPriceAfterTax());
        $this->assertEquals(8, $bill->getTotalItems());
        $this->assertEquals(115, $bill->getTotalTax());
        $this->assertEquals([10 => 15, 20 => 100], $bill->getTotalTaxGrouped());
    }

}