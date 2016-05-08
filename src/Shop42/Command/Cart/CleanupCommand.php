<?php
namespace Shop42\Command\Cart;

use Core42\Command\AbstractCommand;
use Core42\Db\Transaction\TransactionManager;
use Shop42\TableGateway\CartTableGatewayInterface;

class CleanupCommand extends AbstractCommand
{
    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @param int $userId
     * @return CleanupCommand
     */
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;

        return $this;
    }

    /**
     * @param string $sessionId
     * @return CleanupCommand
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }


    /**
     * @return mixed
     */
    protected function execute()
    {
        try {
            $this->getServiceManager()->get(TransactionManager::class)->transaction(function(){
                if (!empty($this->userId)) {
                    $this->cleanupByUserId($this->userId);
                }

                if (!empty($this->sessionId)) {
                    $this->cleanupBySessionId($this->sessionId);
                }

                if (empty($this->sessionId) && empty($this->userId)) {
                    $this->cleanupAll();
                }
            });
        } catch (\Exception $e) {

        }
    }

    /**
     * @param $userId
     */
    protected function cleanupByUserId($userId)
    {
        $this
            ->getTableGateway(CartTableGatewayInterface::class)
            ->getAdapter()
            ->query(
                sprintf(
                    "DELETE FROM %s WHERE userId = ? AND quantity = ?",
                    $this->getTableGateway(CartTableGatewayInterface::class)->getTable()
                ),
                [$userId, 0]
            );
    }

    /**
     * @param $sessionId
     */
    protected function cleanupBySessionId($sessionId)
    {
        $this
            ->getTableGateway(CartTableGatewayInterface::class)
            ->getAdapter()
            ->query(
                sprintf(
                    "DELETE FROM %s WHERE sessionId = ? AND quantity = ?",
                    $this->getTableGateway(CartTableGatewayInterface::class)->getTable()
                ),
                [$sessionId, 0]
            );
    }

    /**
     *
     */
    protected function cleanupAll()
    {
        $this
            ->getTableGateway(CartTableGatewayInterface::class)
            ->getAdapter()
            ->query(
                sprintf(
                    "DELETE FROM %s WHERE quantity = ?",
                    $this->getTableGateway(CartTableGatewayInterface::class)->getTable()
                ),
                [0]
            );
    }
}
