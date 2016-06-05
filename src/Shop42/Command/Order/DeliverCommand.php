<?php
namespace Shop42\Command\Order;

use Core42\Command\AbstractCommand;
use Core42\Db\Transaction\TransactionManager;
use Shop42\EventManager\OrderEventManager;
use Shop42\Model\OrderInterface;
use Shop42\NumberGenerator\NextDeliveryInterface;
use Shop42\NumberGenerator\NextInvoiceInterface;
use Shop42\TableGateway\OrderTableGatewayInterface;

class DeliverCommand extends AbstractCommand
{
    /**
     * @var int
     */
    protected $orderId;

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @param $orderId
     * @return $this
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @param OrderInterface $order
     * @return $this
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function preExecute()
    {
        if ($this->orderId > 0) {
            $this->order = $this->getTableGateway(OrderTableGatewayInterface::class)->selectByPrimary($this->orderId);
        }

        if (empty($this->order)) {
            $this->addError("order", "invalid order");

            return;
        }
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        try {
            $this->getServiceManager()->get(TransactionManager::class)->transaction(function () {

                $invoiceNumber = $this
                    ->getServiceManager()
                    ->get(NextInvoiceInterface::class)
                    ->setOrder($this->order)
                    ->getNextInvoiceNumber();

                $deliveryNumber = $this
                    ->getServiceManager()
                    ->get(NextDeliveryInterface::class)
                    ->setOrder($this->order)
                    ->getNextDeliveryNumber();

                $this->order->setDelivered(new \DateTime())
                    ->setDeliveryNumber($deliveryNumber)
                    ->setInvoiceNumber($invoiceNumber)
                    ->setStatus(OrderInterface::STATUS_FINISH);

                $this->getTableGateway(OrderTableGatewayInterface::class)->update($this->order);

                $this
                    ->getServiceManager()
                    ->get(OrderEventManager::class)
                    ->trigger(OrderEventManager::EVENT_DELIVERY, $this->order);
            });
        } catch (\Exception $e) {
            $this->addError("system", "system error");

            return;
        }

        return $this->order;
    }
}
