<?php
namespace Shop42\Model;

use Core42\Model\AbstractModel;

abstract class AbstractCart extends AbstractModel implements CartInterface
{

    /**
     * @var array
     */
    protected $properties = [
        'id',
        'userId',
        'sessionId',
        'productId',
        'quantity',
    ];

    /**
     * @param int $id
     * @return $this
     * @throws \Exception
     */
    public function setId($id)
    {
        return $this->set("id", $id);
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getId()
    {
        return $this->get("id");
    }

    /**
     * @param mixed $userId
     * @return $this
     * @throws \Exception
     */
    public function setUserId($userId)
    {
        return $this->set("userId", $userId);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getUserId()
    {
        return $this->get("userId");
    }

    /**
     * @param string $sessionId
     * @return $this
     * @throws \Exception
     */
    public function setSessionId($sessionId)
    {
        return $this->set("sessionId", $sessionId);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getSessionId()
    {
        return $this->get("sessionId");
    }

    /**
     * @param mixed $productId
     * @return $this
     * @throws \Exception
     */
    public function setProductId($productId)
    {
        return $this->set("productId", $productId);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getProductId()
    {
        return $this->get("productId");
    }

    /**
     * @param int $quantity
     * @return $this
     * @throws \Exception
     */
    public function setQuantity($quantity)
    {
        return $this->set("quantity", $quantity);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getQuantity()
    {
        return $this->get("quantity");
    }
}
