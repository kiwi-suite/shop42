<?php
namespace Shop42\Command\Payment;

use Core42\Command\AbstractCommand;
use Core42\Db\Transaction\TransactionManager;
use Ixopay\Client\Callback\Result;
use Ixopay\Client\Client;
use Shop42\EventManager\CheckoutEventManager;
use Shop42\Ixopay\Ixopay;
use Shop42\Model\OrderInterface;
use Shop42\NumberGenerator\NextOrderInterface;
use Shop42\TableGateway\OrderTableGatewayInterface;

class CallbackCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $paymentProvider;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var Result
     */
    protected $result;

    /**
     * @param $paymentProvider
     * @return $this
     */
    public function setPaymentProvider($paymentProvider)
    {
        $this->paymentProvider = $paymentProvider;

        return $this;
    }

    /**
     * @param $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @param string $countryCode
     * @return $this
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     *
     */
    protected function preExecute()
    {
        /** @var Client $client */
        $client = $this->getServiceManager()->get(Ixopay::class)->getClient(
            $this->paymentProvider,
            $this->countryCode,
            \Locale::getPrimaryLanguage($this->locale)
        );

        if (!$client->validateCallbackWithGlobals()) {
            $this->addError("invalid", "callback signature invalid");

            return;
        }

        $this->result = $client->readCallback(file_get_contents('php://input'));

        $orderResult = $this->getTableGateway(OrderTableGatewayInterface::class)->select([
            'uuid' => $this->result->getTransactionId(),
        ]);

        if ($orderResult->count() == 0) {
            $this->addError("uuid", "invalid uuid");
            return;
        }

        $this->order = $orderResult->current();
    }

    /**
     *
     */
    protected function execute()
    {
        try {
            $this->getServiceManager()->get(TransactionManager::class)->transaction(function () {

                $this
                    ->getServiceManager()
                    ->get(CheckoutEventManager::class)
                    ->trigger(CheckoutEventManager::EVENT_CALLBACK_PRE, $this->order);


                switch ($this->result->getResult()) {
                    case Result::RESULT_OK:
                        $this->handleSuccess();
                        break;
                    case Result::RESULT_PENDING:
                        $this->handlePending();
                        break;
                    case Result::RESULT_ERROR:
                        $this->handleError();
                        break;
                    case Result::RESULT_INVALID_REQUEST:
                        //TODO throw Exception
                        break;
                }

                if ($this->order->hasChanged()) {
                    $this->getTableGateway(OrderTableGatewayInterface::class)->update($this->order);
                }

                $this
                    ->getServiceManager()
                    ->get(CheckoutEventManager::class)
                    ->trigger(CheckoutEventManager::EVENT_CALLBACK_POST, $this->order);
            });
        } catch (\Exception $e) {
            $this->addError("system", "system error");

            $this
                ->getServiceManager()
                ->get(CheckoutEventManager::class)
                ->trigger(CheckoutEventManager::EVENT_CALLBACK_ERROR, $this->order);

            return;
        }
    }

    /**
     *
     */
    protected function handleError()
    {
        if ($this->order->getPaymentStatus() === OrderInterface::STATUS_ERROR) {
            return;
        }

        $orderNumber = $this
            ->getServiceManager()
            ->get(NextOrderInterface::class)
            ->setOrder($this->order)
            ->getNextOrderNumber();

        $this->order->setPaymentStatus(OrderInterface::PAYMENT_STATUS_ERROR)
            ->setOrderNumber($orderNumber)
            ->setStatus(OrderInterface::STATUS_ERROR);

        $this
            ->getServiceManager()
            ->get(CheckoutEventManager::class)
            ->trigger(CheckoutEventManager::EVENT_CALLBACK_FAIL, $this->order);
    }

    /**
     *
     */
    protected function handleSuccess()
    {
        if ($this->order->getPaymentStatus() === OrderInterface::PAYMENT_STATUS_SUCCESS) {
            return;
        }

        $orderNumber = $this
            ->getServiceManager()
            ->get(NextOrderInterface::class)
            ->setOrder($this->order)
            ->getNextOrderNumber();

        $this->order->setPayed(new \DateTime())
            ->setPaymentStatus(OrderInterface::PAYMENT_STATUS_SUCCESS)
            ->setOrderNumber($orderNumber)
            ->setStatus(OrderInterface::STATUS_OPEN);

        $this
            ->getServiceManager()
            ->get(CheckoutEventManager::class)
            ->trigger(CheckoutEventManager::EVENT_CALLBACK_SUCCESS, $this->order);
    }

    /**
     *
     */
    protected function handlePending()
    {
        if ($this->order->getPaymentStatus() === OrderInterface::PAYMENT_STATUS_PENDING) {
            return;
        }

        $this->order->setPaymentStatus(OrderInterface::PAYMENT_STATUS_PENDING);

        $this
            ->getServiceManager()
            ->get(CheckoutEventManager::class)
            ->trigger(CheckoutEventManager::EVENT_CALLBACK_PENDING, $this->order);
    }
}
