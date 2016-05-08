<?php
namespace Shop42\Command\Stock;

use Core42\Command\AbstractCommand;
use Core42\Db\Transaction\TransactionManager;
use Shop42\Model\ProductInterface;
use Shop42\TableGateway\ProductTableGatewayInterface;

class ChangeCommand extends AbstractCommand
{
    /**
     * @var ProductInterface
     */
    protected $product;

    /**
     * @var int
     */
    protected $productId;

    /**
     * @var int
     */
    protected $stock;

    /**
     * @param int $productId
     * @return ChangeCommand
     */
    public function setProductId($productId)
    {
        $this->productId = (int) $productId;

        return $this;
    }

    /**
     * @param int $stock
     * @return $this
     */
    public function setStock($stock)
    {
        $this->stock = (int) $stock;

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
    }

    /**
     * @return mixed
     */
    protected function execute()
    {
        // Nothing to do, as stock is disabled
        if ($this->product->getStock() === null) {
            return;
        }

        try {
            $this->getServiceManager()->get(TransactionManager::class)->transaction(function(){
                $count = $this
                    ->getTableGateway(ProductTableGatewayInterface::class)
                    ->getAdapter()
                    ->query(
                        sprintf(
                            "UPDATE %s SET stock = stock + ? WHERE id = ? AND CAST(stock AS SIGNED) + ? >= 0",
                            $this->getTableGateway(ProductTableGatewayInterface::class)->getTable()
                        ),
                        [
                            $this->stock,
                            $this->product->getId(),
                            $this->stock
                        ]
                    );

                if ($count->getAffectedRows() == 0) {
                    throw new \Exception("negativ stock");
                }
            });
        } catch (\Exception $e) {
            $this->addError("system", "system error");

            return;
        }
    }
}
