<?php

namespace Shop42Test\Billing;

use Shop42\Billing\Bill;
use Shop42\Billing\Container;
use Shop42\Billing\ItemInterface;
use Shop42\Billing\AbstractItem;

class BillContainerTest extends \PHPUnit_Framework_TestCase {

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

        $container = new Container();

        $container->addItem($this->getItemMock(100, 20, 5, false));
        $container->addItem($this->getItemMock(50, 10, 3, false));

        $bill->addItem($container);

        $container = new Container();

        $container->addItem($this->getItemMock(300, 20, 1, false));
        $container->addItem($this->getItemMock(80, 10, 5, false));

        $bill->addItem($container);

        $container = new Container();

        $container->addItem($this->getItemMock(10, 20, 1, false));
        $container->addItem($this->getItemMock(20, 10, 5, false));

        $bill->addItem($container);

        $this->assertEquals(1460, $bill->getTotalPriceBeforeTax());
        $this->assertEquals(1687, $bill->getTotalPriceAfterTax());
        $this->assertEquals(20, $bill->getTotalQuantity());
        $this->assertEquals(227, $bill->getTotalTaxPrice());

        $this->assertEquals([10 => 65, 20 => 162], $bill->getTotalTaxGrouped());

        foreach ($bill as $key => $container) {
            switch ($key) {
                case 0:
                    $this->assertEquals(650, $bill->getSubTotalPriceBeforeTax());
                    $this->assertEquals(765, $bill->getSubTotalPriceAfterTax());
                    $this->assertEquals(8, $bill->getSubTotalQuantity());
                    $this->assertEquals(115, $bill->getSubTotalTaxPrice());
                    break;
                case 1:
                    $this->assertEquals(1350, $bill->getSubTotalPriceBeforeTax());
                    $this->assertEquals(1565, $bill->getSubTotalPriceAfterTax());
                    $this->assertEquals(14, $bill->getSubTotalQuantity());
                    $this->assertEquals(215, $bill->getSubTotalTaxPrice());
                    break;
                case 2:
                    $this->assertEquals(1460, $bill->getSubTotalPriceBeforeTax());
                    $this->assertEquals(1687, $bill->getSubTotalPriceAfterTax());
                    $this->assertEquals(20, $bill->getSubTotalQuantity());
                    $this->assertEquals(227, $bill->getSubTotalTaxPrice());
                    break;
            }
        }
    }

}