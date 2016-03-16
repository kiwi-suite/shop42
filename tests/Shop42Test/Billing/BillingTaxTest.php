<?php

namespace Shop42Test\Billing;

use Shop42\Billing\AbstractItem;

class BillingTaxTest extends \PHPUnit_Framework_TestCase {

    public function testTaxCalculations()
    {
        /** @var AbstractItem $itemMock */
        $itemMock = $this->getMockForAbstractClass(AbstractItem::class);

        $itemMock->setPrice(99);
        $itemMock->setTax(20);
        $itemMock->setQuantity(5);

        $this->assertEquals(99, $itemMock->getPrice());
        $this->assertEquals(20, $itemMock->getTax());
        $this->assertEquals(5, $itemMock->getQuantity());


        /**
         * test tax included
         */

        $itemMock->setTaxIncluded(true);

        $this->assertEquals(82.50, $itemMock->getSinglePriceBeforeTax());
        $this->assertEquals(412.50, $itemMock->getTotalPriceBeforeTax());
        $this->assertEquals(99, $itemMock->getSinglePriceAfterTax());
        $this->assertEquals(495, $itemMock->getTotalPriceAfterTax());
        $this->assertEquals(16.50, $itemMock->getSingleTaxPrice());
        $this->assertEquals(82.50, $itemMock->getTotalTaxPrice());

        /**
         * test tax not included
         */

        $itemMock->setTaxIncluded(false);

        $this->assertEquals(99, $itemMock->getSinglePriceBeforeTax());
        $this->assertEquals(495, $itemMock->getTotalPriceBeforeTax());
        $this->assertEquals(118.80, $itemMock->getSinglePriceAfterTax());
        $this->assertEquals(594, $itemMock->getTotalPriceAfterTax());
        $this->assertEquals(19.80, $itemMock->getSingleTaxPrice());
        $this->assertEquals(99, $itemMock->getTotalTaxPrice());

        /**
         * test rounding
         */

        $itemMock->setPrice(34.92);
        $itemMock->setTaxIncluded(false);

        $this->assertEquals(41.90, $itemMock->getSinglePriceAfterTax());
        $this->assertEquals(6.98, $itemMock->getSingleTaxPrice());
    }

}