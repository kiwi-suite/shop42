<?php
namespace Shop42\NumberGenerator;

use Shop42\Model\OrderInterface;

interface NextOrderInterface
{
    /**
     * @param OrderInterface $order
     * @return NextOrderInterface
     */
    public function setOrder(OrderInterface $order);

    /**
     * @return string
     */
    public function getNextOrderNumber();
}
