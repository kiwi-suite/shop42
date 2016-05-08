<?php
namespace Shop42\Command\Payment;

use Core42\Command\AbstractCommand;
use Core42\Db\Transaction\TransactionManager;
use Ixopay\Client\Client;
use Ixopay\Client\Data\Customer;
use Ixopay\Client\Transaction\Debit;
use Ixopay\Client\Transaction\Result;
use Rhumsaa\Uuid\Uuid;
use Shop42\Billing\Bill;
use Shop42\Billing\ItemInterface;
use Shop42\Command\Stock\ChangeCommand;
use Shop42\EventManager\CheckoutEventManager;
use Shop42\Ixopay\Ixopay;
use Shop42\Model\OrderInterface;
use Shop42\TableGateway\OrderTableGatewayInterface;

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
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $callbackUrl;

    /**
     * @var string
     */
    protected $successUrl;

    /**
     * @var string
     */
    protected $cancelUrl;

    /**
     * @var string
     */
    protected $errorUrl;

    /**
     * @var Result
     */
    protected $result;

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
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @param string $callbackUrl
     * @return $this
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    /**
     * @param string $successUrl
     * @return $this
     */
    public function setSuccessUrl($successUrl)
    {
        $this->successUrl = $successUrl;

        return $this;
    }

    /**
     * @param string $cancelUrl
     * @return $this
     */
    public function setCancelUrl($cancelUrl)
    {
        $this->cancelUrl = $cancelUrl;

        return $this;
    }

    public function setErrorUrl($errorUrl)
    {
        $this->errorUrl = $errorUrl;

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
                $this->getTableGateway(OrderTableGatewayInterface::class)->insert($this->order);
            });
        } catch (\Exception $e) {
            $this->addError("system", "system error");

            $this
                ->getServiceManager()
                ->get(CheckoutEventManager::class)
                ->trigger(CheckoutEventManager::EVENT_CHECKOUT_ERROR, $this->order);

            return null;
        }

        try {
            /** @var Client $client */
            $client = $this->getServiceManager()->get(Ixopay::class)->getClient(
                $this->order->getPaymentProvider(),
                $this->order->getBillingCountry(),
                \Locale::getPrimaryLanguage($this->locale)
            );

            $debit = new Debit();
            $debit->setCustomer($this->getCustomer())
                ->setCallbackUrl($this->callbackUrl)
                ->setSuccessUrl($this->successUrl)
                ->setErrorUrl($this->errorUrl)
                ->setCancelUrl($this->cancelUrl)
                ->setAmount($this->bill->getTotalPriceAfterTax())
                ->setCurrency($this->bill->getCurrency())
                ->setTransactionId($this->order->getUuid());

            $extraData = $this
                ->getServiceManager()
                ->get(Ixopay::class)
                ->getExtraParams($this->order->getPaymentProvider(), $this->order->getBillingCountry());

            foreach ($extraData as $name => $value) {
                $debit->addExtraData($name, $value);
            }

            $this->result = $client->debit($debit);

            $this
                ->getServiceManager()
                ->get(CheckoutEventManager::class)
                ->trigger(CheckoutEventManager::EVENT_CHECKOUT_POST, $this->order);
        } catch (\Exception $e) {
            $this->addError("system", "system error");

            $this
                ->getServiceManager()
                ->get(CheckoutEventManager::class)
                ->trigger(CheckoutEventManager::EVENT_CHECKOUT_ERROR, $this->order);

            return null;
        }

        return $this->result;
    }

    /**
     * @return Customer
     */
    protected function getCustomer()
    {
        $customer = new Customer();
        
        $customer->setEmail($this->order->getBillingEmail());
        $customer->setFirstName($this->order->getBillingFirstName());
        $customer->setLastName($this->order->getBillingLastName());
        $customer->setCompany($this->order->getBillingCompany());
        if ($this->order->getBillingBirthDate() instanceof \DateTime){
            $customer->setBirthDate($this->order->getBillingBirthDate());
        }
        if ($this->order->getBillingGender() === OrderInterface::GENDER_MALE) {
            $customer->setGender(Customer::GENDER_MALE);
        } elseif ($this->order->getBillingGender() === OrderInterface::GENDER_FEMALE) {
            $customer->setGender(Customer::GENDER_FEMALE);
        }

        $customer->setIpAddress(
            empty($_SERVER['X-Forwarded-For']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['X-Forwarded-For']
        );

        $customer->setBillingAddress1($this->order->getBillingAddress1());
        $customer->setBillingAddress2($this->order->getBillingAddress2());
        $customer->setBillingCity($this->order->getBillingCity());
        $customer->setBillingCountry($this->order->getBillingCountry());
        $customer->setBillingPhone($this->order->getBillingPhone());
        $customer->setBillingPostcode($this->order->getBillingPostcode());
        $customer->setBillingState($this->order->getBillingState());

        $customer->setShippingAddress1($this->order->getShippingAddress1());
        $customer->setShippingAddress2($this->order->getShippingAddress2());
        $customer->setShippingCity($this->order->getShippingCity());
        $customer->setShippingCompany($this->order->getShippingCompany());
        $customer->setShippingCountry($this->order->getShippingCountry());
        $customer->setShippingState($this->order->getShippingState());
        $customer->setShippingPhone($this->order->getShippingPhone());
        $customer->setShippingFirstName($this->order->getShippingFirstName());
        $customer->setShippingLastName($this->order->getShippingLastName());

        return $customer;
    }
}
