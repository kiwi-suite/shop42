<?php
namespace Shop42\Command\Payment;

use Core42\Command\AbstractCommand;
use Core42\Db\Transaction\TransactionManager;
use Ixopay\Client\Callback\Result;
use Shop42\EventManager\CheckoutEventManager;
use Shop42\Model\OrderInterface;

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
     * @param $paymentProvider
     * @return $this
     */
    public function setPaymentMethod($paymentProvider)
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
     *
     */
    protected function preExecute()
    {
        //TODO ixopay client
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
                    ->trigger(CheckoutEventManager::EVENT_CALLBACK_PRE, $this->order);


                switch (true/*change to ixopaylib*/) {
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

    protected function handleError()
    {
        $this
            ->getServiceManager()
            ->get(CheckoutEventManager::class)
            ->trigger(CheckoutEventManager::EVENT_CALLBACK_FAIL, $this->order);
    }

    protected function handleSuccess()
    {
        $this
            ->getServiceManager()
            ->get(CheckoutEventManager::class)
            ->trigger(CheckoutEventManager::EVENT_CALLBACK_SUCCESS, $this->order);
    }

    protected function handlePending()
    {
        $this
            ->getServiceManager()
            ->get(CheckoutEventManager::class)
            ->trigger(CheckoutEventManager::EVENT_CALLBACK_PENDING, $this->order);
    }
}
