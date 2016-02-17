<?php
namespace Shop42\Command\Payment;

use Core42\Command\AbstractCommand;
use Ixopay\Client\Data\Customer;
use Ixopay\Client\Data\Item;
use Ixopay\Client\Transaction\Debit;
use Rhumsaa\Uuid\Uuid;
use Shop42\Model\OrderInterface;
use Shop42\Model\OrderItemInterface;
use Zend\Stdlib\Hydrator\ClassMethods;

class CreateCommand extends AbstractCommand
{
    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var OrderItemInterface[]
     */
    protected $orderItems = [];

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
     * @param array $orderItems
     * @return $this
     */
    public function setOrderItems(array $orderItems)
    {
        $this->orderItems = $orderItems;

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

        $this->order->setUuid(Uuid::uuid4());
        //$this->order->setPaymentStatus(OrderInterface::PAYMENT_STATUS_NEW);
    }

    /**
     *
     */
    protected function execute()
    {

        $this->getTableGateway('Shop42\Order')->insert($this->order);

        $transaction = new Debit();


    }

    /**
     * @return Customer
     */
    protected function getCustomer()
    {
        $data = array_filter($this->order->toArray(), function($value, $key){
            if (substr($key, 0, 1) != "billing" && substr($key, 0, 1) != "shipping") {
                return false;
            }

            if (empty($value)) {
                return false;
            }

            return true;
        }, ARRAY_FILTER_USE_BOTH);

        $hydrator = new ClassMethods(false);
        /** @var Customer $customer */
        $customer = $hydrator->hydrate($data, new Customer());
        $customer->setIpAddress(
            empty($_SERVER['X-Forwarded-For']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['X-Forwarded-For']
        );

        return $customer;
    }

    /**
     * @return array
     */
    protected function getItems()
    {
        $items = [];

        foreach ($this->orderItems as $_orderItem) {
            $item = new Item();
            $item->setName($_orderItem->getName())
                ->setIdentification($_orderItem->getProductId())
                ->setPrice($_orderItem->getPrice())
                ->setCurrency($_orderItem->getCurrency())
                ->setQuantity($_orderItem->getQuantity())
                ->setExtraData("vat", $_orderItem->getTax());

            $items[] = $item;
        }

        return $items;
    }
}
