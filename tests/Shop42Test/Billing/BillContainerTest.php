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
        $container = new Container();

        $bill = new Bill();

        $bill->addItem($this->getItemMock(100, 20, 5, false));
        $bill->addItem($this->getItemMock(50, 10, 3, false));

        $container->addItem($bill);

        $bill = new Bill();

        $bill->addItem($this->getItemMock(300, 20, 1, false));
        $bill->addItem($this->getItemMock(80, 10, 5, false));

        $container->addItem($bill);

        $bill = new Bill();

        $bill->addItem($this->getItemMock(10, 20, 1, false));
        $bill->addItem($this->getItemMock(20, 10, 5, false));

        $container->addItem($bill);

        $this->assertEquals(1460, $container->getTotalPriceBeforeTax());
        $this->assertEquals(1687, $container->getTotalPriceAfterTax());
        $this->assertEquals(20, $container->getTotalQuantity());
        $this->assertEquals(227, $container->getTotalTaxPrice());

        foreach ($container as $key => $bill) {
            switch ($key) {
                case 0:
                    $this->assertEquals(650, $container->getSubTotalPriceBeforeTax());
                    $this->assertEquals(765, $container->getSubTotalPriceAfterTax());
                    $this->assertEquals(8, $container->getSubTotalQuantity());
                    $this->assertEquals(115, $container->getSubTotalTaxPrice());
                    break;
                case 1:
                    $this->assertEquals(1350, $container->getSubTotalPriceBeforeTax());
                    $this->assertEquals(1565, $container->getSubTotalPriceAfterTax());
                    $this->assertEquals(14, $container->getSubTotalQuantity());
                    $this->assertEquals(215, $container->getSubTotalTaxPrice());
                    break;
                case 2:
                    $this->assertEquals(1460, $container->getSubTotalPriceBeforeTax());
                    $this->assertEquals(1687, $container->getSubTotalPriceAfterTax());
                    $this->assertEquals(20, $container->getSubTotalQuantity());
                    $this->assertEquals(227, $container->getSubTotalTaxPrice());
                    break;
            }
        }
    }

}