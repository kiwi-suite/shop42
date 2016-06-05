<?php
namespace Shop42\Model;

use Core42\Model\AbstractModel;

abstract class AbstractProductI18n extends AbstractModel implements ProductI18nInterface
{

    /**
     * @var array
     */
    protected $properties = [
        'productId',
        'locale',
        'name',
        'price',
        'tax',
        'currency',
    ];

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
     * @param string $locale
     * @return $this
     * @throws \Exception
     */
    public function setLocale($locale)
    {
        return $this->set("locale", $locale);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getLocale()
    {
        return $this->get("locale");
    }

    /**
     * @param string $name
     * @return $this
     * @throws \Exception
     */
    public function setName($name)
    {
        return $this->set("name", $name);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getName()
    {
        return $this->get("name");
    }

    /**
     * @param float $price
     * @return $this
     * @throws \Exception
     */
    public function setPrice($price)
    {
        return $this->set("price", $price);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getPrice()
    {
        return $this->get("price");
    }

    /**
     * @param int $tax
     * @return $this
     * @throws \Exception
     */
    public function setTax($tax)
    {
        return $this->set("tax", $tax);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getTax()
    {
        return $this->get("tax");
    }

    /**
     * @param string $currency
     * @return $this
     * @throws \Exception
     */
    public function setCurrency($currency)
    {
        return $this->set("currency", $currency);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getCurrency()
    {
        return $this->get("currency");
    }
}
