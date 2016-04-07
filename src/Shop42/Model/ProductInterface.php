<?php
namespace Shop42\Model;

use Core42\Model\ModelInterface;

interface ProductInterface extends ModelInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

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
     * @return string
     */
    public function getUuid();

    /**
     * @param $uuid
     * @return $this
     */
    public function setUuid($uuid);

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param int $stock
     * @return $this
     */
    public function setStock($stock);

    /**
     * @return int
     */
    public function getStock();

    /**
     * @param int $maxQuantity
     * @return $this
     */
    public function setMaxQuantity($maxQuantity);

    /**
     * @return int
     */
    public function getMaxQuantity();
}
