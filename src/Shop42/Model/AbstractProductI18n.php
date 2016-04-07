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

    public function setProductId($productId)
    {
        return $this->set("productId", $productId);
    }

    public function getProductId()
    {
        return $this->get("productId");
    }

    public function setLocale($locale)
    {
        return $this->set("locale", $locale);
    }

    public function getLocale()
    {
        return $this->get("locale");
    }

    public function setName($name)
    {
        return $this->set("name", $name);
    }

    public function getName()
    {
        return $this->get("name");
    }

    public function setPrice($price)
    {
        return $this->set("price", $price);
    }

    public function getPrice()
    {
        return $this->get("price");
    }

    public function setTax($tax)
    {
        return $this->set("tax", $tax);
    }

    public function getTax()
    {
        return $this->get("tax");
    }

    public function setCurrency($currency)
    {
        return $this->set("currency", $currency);
    }

    public function getCurrency()
    {
        return $this->get("currency");
    }


}
