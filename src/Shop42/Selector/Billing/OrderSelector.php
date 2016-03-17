<?php

namespace Shop42\Selector\Billing;

use Core42\Selector\AbstractDatabaseSelector;
use Shop42\Billing\Bill;
use Shop42\Model\OrderInterface;

use Shop42\Model\OrderItemInterface;
use Zend\Db\Sql\Select;
use Core42\Db\ResultSet\ResultSet;

class OrderSelector extends AbstractDatabaseSelector {

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @param OrderInterface $order
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        $this->order = $this->getTableGateway(OrderInterface::class)->selectByPrimary($orderId);
        return $this;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        $bill = new Bill;
        $bill->setItems($this->getTableGateway(OrderItemInterface::class)->select(['orderId' => $this->order->getId()]));

        return [
            'order' => $this->order,
            'bill'  => $bill,
        ];
    }

    /**
     * @return Select|string|ResultSet
     */
    protected function getSelect()
    {
        return '';
    }

}