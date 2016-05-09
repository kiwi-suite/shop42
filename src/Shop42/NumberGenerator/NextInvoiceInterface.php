<?php
namespace Shop42\NumberGenerator;

use Shop42\Model\OrderInterface;

interface NextInvoiceInterface
{
    /**
     * @param OrderInterface $order
     * @return NextInvoiceInterface
     */
    public function setOrder(OrderInterface $order);

    /**
     * @return string
     */
    public function getNextInvoiceNumber();
}
