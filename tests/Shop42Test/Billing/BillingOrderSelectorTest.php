<?php

namespace Shop42Test\Billing;

use Shop42\Billing\Bill;
use Shop42\Billing\ItemInterface;
use Shop42\Billing\AbstractItem;
use Shop42\Model\OrderInterface;
use Shop42\Model\OrderItemInterface;
use Core42\Test\PHPUnit\Bootstrap;
use Shop42\Selector\Billing\OrderSelector;
use Zend\ServiceManager\ServiceManager;

class BillOrderSelectorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp()
    {
        parent::setUp();

        $this->serviceManager = Bootstrap::getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        $this->serviceManager->get('TableGateway')->setAllowOverride(true);
    }

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

    public function testSelector()
    {
        /** @var OrderInterface $orderModel */
        $orderModel = $this->getMockBuilder(OrderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $orderModel->method("getId")->willReturn(1);

        $this->assertEquals(1, $orderModel->getId());

        $orderItemTableGatewayMock = $this->getMockBuilder('\Core42\Db\TableGateway\AbstractTableGateway')
            ->disableOriginalConstructor()
            ->getMock();

        $orderItemTableGatewayMock->method('select')
            ->will($this->returnValue([
                $this->getItemMock(100, 20, 5, false),
                $this->getItemMock(50, 10, 3, false),
            ]));
        $this->serviceManager->get('TableGateway')->setService(OrderItemInterface::class, $orderItemTableGatewayMock);

        $orderSelector = new OrderSelector($this->serviceManager);
        $result = $orderSelector->setOrder($orderModel)->getResult();

        $this->assertEquals(650, $result['bill']->getTotalPriceBeforeTax());
        $this->assertEquals(765, $result['bill']->getTotalPriceAfterTax());
        $this->assertEquals(8, $result['bill']->getTotalItems());
        $this->assertEquals(115, $result['bill']->getTotalTax());
        $this->assertEquals([10 => 15, 20 => 100], $result['bill']->getTotalTaxGrouped());
    }

}