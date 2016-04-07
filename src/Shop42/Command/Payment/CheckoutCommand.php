<?php
namespace Shop42\Command\Payment;

use Core42\Command\AbstractCommand;
use Core42\Db\Transaction\TransactionManager;
use Rhumsaa\Uuid\Uuid;
use Shop42\Billing\Bill;
use Shop42\Billing\ItemInterface;
use Shop42\Command\Stock\ChangeCommand;
use Shop42\EventManager\CheckoutEventManager;
use Shop42\Model\OrderInterface;
use Shop42\Model\OrderItemInterface;

class CheckoutCommand extends AbstractCommand
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

                $this
                    ->getServiceManager()
                    ->get(CheckoutEventManager::class)
                    ->trigger(CheckoutEventManager::EVENT_CHECKOUT_PRE, $this->order);

                /** @var ItemInterface $item */
                foreach ($this->bill as $item) {
                    /** @var ChangeCommand $cmd */
                    $cmd = $this->getCommand(ChangeCommand::class);
                    $cmd->setStock((-1) * $item->getTotalQuantity())
                        ->setProductId($item->getProductId())
                        ->run();

                    if ($cmd->hasErrors()) {
                        throw new \Exception("stock error");
                    }
                }

                $this->order->setCreated(new \DateTime());
                $this->getTableGateway(OrderInterface::class)->insert($this->order);

                $this
                    ->getServiceManager()
                    ->get(CheckoutEventManager::class)
                    ->trigger(CheckoutEventManager::EVENT_CHECKOUT_POST, $this->order);
            });
        } catch (\Exception $e) {
            $this->addError("system", "system error");

            $this
                ->getServiceManager()
                ->get(CheckoutEventManager::class)
                ->trigger(CheckoutEventManager::EVENT_CHECKOUT_ERROR, $this->order);

            return;
        }
    }
}
