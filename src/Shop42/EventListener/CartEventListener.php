<?php
namespace Shop42\EventListener;

use Shop42\Command\Cart\CleanupCommand;
use Shop42\EventManager\CartEventManager;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class CartEventListener extends AbstractListenerAggregate
{
    /**
     * @var CleanupCommand
     */
    protected $cleanupCommand;

    /**
     * CartEventListener constructor.
     * @param CleanupCommand $cleanupCommand
     */
    public function __construct(CleanupCommand $cleanupCommand)
    {
        $this->cleanupCommand = $cleanupCommand;
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
        $this->listeners[] = $events->attach(CartEventManager::EVENT_RELATIVE_NEW_POST, array($this, 'cleanup'));
        $this->listeners[] = $events->attach(CartEventManager::EVENT_RELATIVE_UPDATE_POST, array($this, 'cleanup'));
    }

    /**
     * @param Event $event
     */
    public function cleanup(Event $event)
    {
        $this->cleanupCommand->setSessionId($event->getTarget()->getSessionId());
        if (!empty($event->getTarget()->getUserId())) {
            $this->cleanupCommand->setUserId($event->getTarget()->getUserId());
        }
        $this->cleanupCommand->run();
    }
}
