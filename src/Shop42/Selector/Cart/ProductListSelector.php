<?php
namespace Shop42\Selector\Cart;

use Core42\Selector\AbstractDatabaseSelector;
use Shop42\Model\CartInterface;
use Shop42\Model\ProductI18nInterface;
use Shop42\Model\ProductInterface;
use Shop42\TableGateway\CartTableGatewayInterface;
use Shop42\TableGateway\ProductI18nTableGatewayInterface;
use Shop42\TableGateway\ProductTableGatewayInterface;

class ProductListSelector extends AbstractDatabaseSelector
{
    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @param $sessionId
     * @return $this
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @param $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return array
     */
    public function getResult()
    {
        $where = [
            'sessionId' => $this->sessionId,
        ];
        if (!empty($this->userId)) {
            $where = [
                'userId' => $this->userId,
            ];
        }

        $result = $this->getTableGateway(CartTableGatewayInterface::class)->select($where);
        if ($result->count() == 0) {
            return [];
        }

        $items = [];

        $productIds = [];
        /** @var CartInterface $cart */
        foreach ($result as $cart) {
            $productIds[] = $cart->getProductId();

            $items[$cart->getProductId()] = [
                'cart' => $cart,
            ];
        }

        $productResult = $this->getTableGateway(ProductTableGatewayInterface::class)->select(['id' => $productIds]);
        $productI18nResult = $this->getTableGateway(ProductI18nTableGatewayInterface::class)->select([
            'locale' => $this->locale,
            'productId' => $productIds
        ]);

        /** @var ProductInterface $product */
        foreach ($productResult as $product) {
            if (!isset($items[$product->getId()])) continue;
            $items[$product->getId()]['product'] = $product;
        }

        /** @var ProductI18nInterface $productI18n */
        foreach ($productI18nResult as $productI18n) {
            if (!isset($items[$productI18n->getProductId()])) continue;

            $items[$productI18n->getProductId()]['productI18n'] = $productI18n;
        }

        foreach ($items as $key => $item) {
            if (empty($item['product'])) {
                unset($items[$key]);
            }
            if (empty($item['productI18n'])) {
                unset($items[$key]);
            }
        }

        return $items;
    }

    /**
     * @return string
     */
    protected function getSelect()
    {
        return '';
    }
}
