<?php
namespace Shop42\EventListener;

use Shop42\Billing\ItemInterface;
use Shop42\Command\Stock\ChangeCommand;
use Shop42\EventManager\CheckoutEventManager;
use Shop42\Model\OrderInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class CheckoutEventListener extends AbstractListenerAggregate
{
    /**
     * @var ChangeCommand
     */
    protected $changeCommand;

    /**
     * CheckoutEventListener constructor.
     * @param ChangeCommand $changeCommand
     */
    public function __construct(ChangeCommand $changeCommand)
    {
        $this->changeCommand = $changeCommand;
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(CheckoutEventManager::EVENT_CALLBACK_FAIL, array($this, 'stockChange'));
    }

    /**
     * @param Event $event
     */
    public function stockChange(Event $event)
    {
        /** @var OrderInterface $order */
        $order = $event->getTarget();

        /** @var ItemInterface $item */
        foreach ($order->getBill() as $item) {
            $changeCommand = clone $this->changeCommand;
            $changeCommand->setProductId($item->getProductId())
                ->setStock($item->getTotalQuantity())
                ->run();
        }
    }
}
