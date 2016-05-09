<?php
namespace Shop42\NumberGenerator;

use Shop42\Model\OrderInterface;
use Shop42\TableGateway\AbstractOrderTableGateway;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;

class NextDelivery implements NextDeliveryInterface
{
    /**
     * @var string
     */
    protected $numberPrefix;

    /**
     * @var OrderInterface
     */
    protected $order;

    /**
     * @var AbstractOrderTableGateway
     */
    protected $orderTableGateway;

    /**
     * NextOrder constructor.
     * @param AbstractOrderTableGateway $orderTableGateway
     */
    public function __construct(AbstractOrderTableGateway $orderTableGateway)
    {
        $this->orderTableGateway = $orderTableGateway;

        $this->numberPrefix = "D" . date("Y");
    }

    /**
     * @param OrderInterface $order
     * @return NextOrderInterface
     */
    public function setOrder(OrderInterface $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getNextDeliveryNumber()
    {
        $result = $this->orderTableGateway->select(function(Select $select) {
            $select->columns([
                'deliveryNumber' => new Expression("COUNT(*) + 1")
            ]);
            $select->where(function (Where $where) {
                $where->isNotNull("deliveryNumber");
            });
        });

        if ($result->count() == 0) {
            return $this->numberPrefix . str_pad("1", 7, "0", STR_PAD_LEFT);
        }

        /** @var OrderInterface $order */
        $order = $result->current();
        return $this->numberPrefix . str_pad($order->getDeliveryNumber(), 7, "0", STR_PAD_LEFT);
    }
}
