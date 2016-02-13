<?php
namespace Shop42\Model;

use Core42\Model\ModelInterface;

interface CartInterface extends ModelInterface
{
    /**
     * @param mixed $userId
     * @return $this
     */
    public function setUserId($userId);

    /**
     * @return mixed
     */
    public function getUserId();

    /**
     * @param string $sessionId
     * @return $this
     */
    public function setSessionId($sessionId);

    /**
     * @return string
     */
    public function getSessionId();

    /**
     * @param mixed $productId
     * @return $this
     */
    public function setProductId($productId);

    /**
     * @return mixed
     */
    public function getProductId();

    /**
     * @param int $amount
     * @return $this
     */
    public function setAmount($amount);

    /**
     * @return int
     */
    public function getAmount();
}
