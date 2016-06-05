<?php
namespace Shop42\Command\Cart;

use Core42\Command\AbstractCommand;
use Core42\Db\Transaction\TransactionManager;
use Shop42\EventManager\CartEventManager;
use Shop42\Model\CartInterface;
use Shop42\Model\ProductInterface;
use Shop42\TableGateway\CartTableGatewayInterface;
use Shop42\TableGateway\ProductTableGatewayInterface;

class CartUpdateCommand extends AbstractCommand
{
    const MODE_RELATIVE = 'relative';
    const MODE_ABSOLUTE = 'absolute';

    /**
     * @var int
     */
    protected $userId;

    /**
     * @var string
     */
    protected $sessionId;

    /**
     * @var int
     */
    protected $productId;

    /**
     * @var int
     */
    protected $quantity = 0;

    /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * @var string
     */
    protected $updateMode = self::MODE_RELATIVE;

    /**
     * @var CartInterface
     */
    protected $result;

    /**
     * @param int $userId
     * @return CartUpdateCommand
     */
    public function setUserId($userId)
    {
        $this->userId = (int) $userId;

        return $this;
    }

    /**
     * @param string $sessionId
     * @return CartUpdateCommand
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * @param int $productId
     * @return CartUpdateCommand
     */
    public function setProductId($productId)
    {
        $this->productId = (int) $productId;

        return $this;
    }

    /**
     * @param int $quantity
     * @return CartUpdateCommand
     */
    public function setQuantity($quantity)
    {
        $this->quantity = (int) $quantity;

        return $this;
    }

    /**
     * @param $updateMode
     * @return CartUpdateCommand
     */
    public function setUpdateMode($updateMode)
    {
        $this->updateMode = $updateMode;

        return $this;
    }

    /**
     * @throws \Exception
     */
    protected function preExecute()
    {
        $this->product = $this->getTableGateway(ProductTableGatewayInterface::class)->selectByPrimary($this->productId);
        if (empty($this->product)) {
            $this->addError("productId", "invalid productId");

            return;
        }

        if ($this->product->getStatus() !== ProductInterface::STATUS_ACTIVE) {
            $this->addError("productId", "product isn't valid anymore");

            return;
        }

        if ($this->product->getMaxQuantity() !== null && $this->quantity > $this->product->getMaxQuantity()) {
            $this->addError("maxQuantity", "maxQuantity reached");

            return;
        }

        if (empty($this->sessionId)) {
            $this->addError("sessionId", "invalid sessionId");

            return;
        }

        if ($this->quantity === 0 && $this->updateMode == self::MODE_RELATIVE) {
            $this->addError("quantity", "nothing to do");

            return;
        }
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        try {
            $this->getServiceManager()->get(TransactionManager::class)->transaction(function () {
                $where = [
                    'sessionId' => $this->sessionId,
                    'productId' => $this->product->getId(),
                ];
                if (!empty($this->userId)) {
                    $where = [
                        'userId' => $this->userId,
                        'productId' => $this->product->getId(),
                    ];
                }

                $result = $this->getTableGateway(CartTableGatewayInterface::class)->select($where);
                if ($result->count() == 0) {
                    $this->result = $this->insertNewItem();

                    return;
                }

                /** @var CartInterface $cart */
                $cart = $result->current();
                if ($this->product->getMaxQuantity() !== null
                    && $cart->getQuantity() + $this->quantity > $this->product->getMaxQuantity()
                ) {
                    $this->addError("maxQuantity", "maxQuantity reached");

                    return;
                }
                $this->result = $this->updateCurrentItem($cart);
            });
        } catch (\Exception $e) {
            $this->addError("system", "system error");

            return;
        }

        return $this->result;
    }

    /**
     * @return CartInterface
     */
    protected function insertNewItem()
    {
        /** @var CartInterface $cart */
        $cart = $this->getTableGateway(CartTableGatewayInterface::class)->getModel();
        $cart->setProductId($this->product->getId())
            ->setSessionId($this->sessionId)
            ->setQuantity($this->quantity);

        if (!empty(($this->userId))) {
            $cart->setUserId($this->userId);
        }

        $this
            ->getServiceManager()
            ->get(CartEventManager::class)
            ->trigger(CartEventManager::EVENT_PREPARE_NEW, $cart);

        $this->getTableGateway(CartTableGatewayInterface::class)->insert($cart);

        $this
            ->getServiceManager()
            ->get(CartEventManager::class)
            ->trigger(CartEventManager::EVENT_FINISH_NEW, $cart);

        return $cart;
    }

    /**
     * @param CartInterface $cart
     * @return CartInterface
     */
    protected function updateCurrentItem(CartInterface $cart)
    {
        $this
            ->getServiceManager()
            ->get(CartEventManager::class)
            ->trigger(CartEventManager::EVENT_PREPARE_EXISTENT, $cart);

        if ($this->updateMode === self::MODE_ABSOLUTE) {
            $cart->setQuantity($this->quantity);
            $this->getTableGateway(CartTableGatewayInterface::class)->update($cart);
        } else {
            $this
                ->getTableGateway(CartTableGatewayInterface::class)
                ->getAdapter()
                ->query(
                    sprintf(
                        "UPDATE %s SET quantity = quantity + ? WHERE id = ?",
                        $this->getTableGateway(CartTableGatewayInterface::class)->getTable()
                    ),
                    [
                        $this->quantity,
                        $cart->getId()
                    ]
                );

            $this->getTableGateway(CartTableGatewayInterface::class)->refresh($cart);
        }

        $this
            ->getServiceManager()
            ->get(CartEventManager::class)
            ->trigger(CartEventManager::EVENT_FINISH_EXISTENT, $cart);

        return $cart;
    }
}
