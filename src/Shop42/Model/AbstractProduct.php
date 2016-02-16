<?php
namespace Shop42\Model;

use Core42\Model\AbstractModel;

abstract class AbstractProduct extends AbstractModel implements ProductInterface
{

    /**
     * @var array
     */
    protected $properties = [
        'id',
        'uuid',
        'status',
        'stock',
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
     * @return string
     * @throws \Exception
     */
    public function getUuid()
    {
        return $this->get("uuid");
    }

    /**
     * @param string $uuid
     * @return $this
     * @throws \Exception
     */
    public function setUuid($uuid)
    {
        return $this->set("uuid", $uuid);
    }

    /**
     * @param string $status
     * @return $this
     * @throws \Exception
     */
    public function setStatus($status)
    {
        return $this->set("status", $status);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getStatus()
    {
        return $this->get("status");
    }

    /**
     * @param int $stock
     * @return $this
     * @throws \Exception
     */
    public function setStock($stock)
    {
        return $this->set("stock", $stock);
    }

    /**
     * @return int|null
     * @throws \Exception
     */
    public function getStock()
    {
        return $this->get("stock");
    }
}
