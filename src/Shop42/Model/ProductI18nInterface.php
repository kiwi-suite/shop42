<?php
namespace Shop42\Model;

use Core42\Model\ModelInterface;

interface ProductI18nInterface extends ModelInterface
{
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
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale);

    /**
     * @return string
     */
    public function getLocale();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param float $price
     * @return $this
     */
    public function setPrice($price);

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @param int $tax
     * @return mixed
     */
    public function setTax($tax);

    /**
     * @return int
     */
    public function getTax();

    /**
     * @param string $currency
     * @return mixed
     */
    public function setCurrency($currency);

    /**
     * @return string
     */
    public function getCurrency();
}
