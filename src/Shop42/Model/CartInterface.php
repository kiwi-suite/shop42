<?php
namespace Shop42\Model;

use Core42\Model\ModelInterface;

interface CartInterface extends ModelInterface
{
    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId);

    /**
     * @return int
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
     * @param int $quantity
     * @return $this
     */
    public function setQuantity($quantity);

    /**
     * @return int
     */
    public function getQuantity();
}
