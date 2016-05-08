<?php
namespace Shop42\Command\Cart;

use Core42\Command\AbstractCommand;
use Core42\Db\Transaction\TransactionManager;
use Shop42\TableGateway\CartTableGatewayInterface;

class ClearCommand extends AbstractCommand
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
     * @return ClearCommand
     */
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;

        return $this;
    }

    /**
     * @param string $sessionId
     * @return ClearCommand
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
                    $this->clearByUserId($this->userId);
                }

                if (!empty($this->sessionId)) {
                    $this->clearBySessionId($this->sessionId);
                }
            });
        } catch (\Exception $e) {

        }
    }

    /**
     * @param $userId
     */
    protected function clearByUserId($userId)
    {
        $this
            ->getTableGateway(CartTableGatewayInterface::class)
            ->getAdapter()
            ->query(
                sprintf(
                    "DELETE FROM %s WHERE userId = ?",
                    $this->getTableGateway(CartTableGatewayInterface::class)->getTable()
                ),
                [$userId]
            );
    }

    /**
     * @param $sessionId
     */
    protected function clearBySessionId($sessionId)
    {
        $this
            ->getTableGateway(CartTableGatewayInterface::class)
            ->getAdapter()
            ->query(
                sprintf(
                    "DELETE FROM %s WHERE sessionId = ?",
                    $this->getTableGateway(CartTableGatewayInterface::class)->getTable()
                ),
                [$sessionId]
            );
    }

}
