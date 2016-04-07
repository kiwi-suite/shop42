<?php
namespace Shop42\Command\Payment;

use Core42\Command\AbstractCommand;
use Core42\Db\Transaction\TransactionManager;
use Rhumsaa\Uuid\Uuid;
use Shop42\Billing\Bill;
use Shop42\Model\OrderInterface;
use Shop42\Model\OrderItemInterface;

class CreateCommand extends AbstractCommand
{
    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var Bill
     */
    protected $bill;

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
     * @param Bill $bill
     * @return $this
     */
    public function setBill(Bill $bill)
    {
        $this->bill = $bill;

        return $this;
    }


    /**
     *
     */
    protected function preExecute()
    {
        if (!($this->order instanceof OrderInterface)) {
            $this->addError("order", "invalid order");

            return ;
        }

        if (empty($this->order->getUuid())) {
            $this->order->setUuid(Uuid::uuid4());
        }

        $this->order->setPaymentStatus(OrderInterface::PAYMENT_STATUS_NEW);
        $this->order->setStatus(OrderInterface::STATUS_INCOMPLETE);
        $this->order->setTotalQuantity($this->bill->getTotalQuantity());
        $this->order->setTotalPriceAfterTax($this->bill->getTotalPriceAfterTax());
        $this->order->setTotalPriceBeforeTax($this->bill->getTotalPriceBeforeTax());
        $this->order->setBill($this->bill);
    }

    /**
     *
     */
    protected function execute()
    {
        try {
            $this->getServiceManager()->get(TransactionManager::class)->transaction(function(){
                $this->order->setCreated(new \DateTime());
                $this->getTableGateway(OrderInterface::class)->insert($this->order);
            });
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            die();
        }
    }
}
