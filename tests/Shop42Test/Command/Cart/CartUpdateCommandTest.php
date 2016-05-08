<?php
namespace Shop42Test\Command\Cart;

use Core42\Db\ResultSet\ResultSet;
use Core42\Db\Transaction\TransactionManager;
use Core42\Model\GenericModel;
use Core42\Test\PHPUnit\Bootstrap;
use Shop42\Command\Cart\CartUpdateCommand;
use Shop42\EventManager\CartEventManager;
use Shop42\Model\ProductInterface;
use Shop42\TableGateway\CartTableGatewayInterface;
use Shop42\TableGateway\ProductTableGatewayInterface;
use Zend\ServiceManager\ServiceManager;

class CartUpdateCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function setUp()
    {
        parent::setUp();

        $this->serviceManager = Bootstrap::getServiceManager();
        $this->serviceManager->setAllowOverride(true);

        $this->serviceManager->get('TableGateway')->setAllowOverride(true);

        $this->serviceManager->setService(CartEventManager::class, $this->getMock(CartEventManager::class));

        $transactionManager = $this->getMockBuilder(TransactionManager::class)
            ->enableProxyingToOriginalMethods()
            ->setConstructorArgs([[]])
            ->getMock();
        $this->serviceManager->setService(TransactionManager::class, $transactionManager);
    }

    public function testInactiveProduct()
    {
        $productTableGatewayMock = $this->getMockBuilder('\Core42\Db\TableGateway\AbstractTableGateway')
            ->disableOriginalConstructor()
            ->getMock();

        $productModel = $this->getMockBuilder(ProductTableGatewayInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productTableGatewayMock->method('selectByPrimary')
            ->will($this->returnCallback(function($id) use ($productModel){
                if ($id == 1) {
                    return null;
                }
                $productModel->method('getStatus')
                    ->willReturn(ProductInterface::STATUS_ACTIVE);

                return $productModel;
            }));
        $this->serviceManager->get('TableGateway')->setService(
            ProductTableGatewayInterface::class,
            $productTableGatewayMock
        );

        $cmd = new CartUpdateCommand($this->serviceManager);

        $cmd->setProductId(1)
            ->setUserId(1)
            ->setSessionId(uniqid())
            ->setQuantity(2);

        $result = $cmd->run();

        $this->assertNull($result);
        $this->assertTrue($cmd->hasErrors());

        $cmd->setProductId(2)
            ->setUserId(1)
            ->setSessionId(uniqid())
            ->setQuantity(2);

        $result = $cmd->run();
        $this->assertNull($result);
        $this->assertTrue($cmd->hasErrors());

    }

    public function testRelativeNewUpdate()
    {
        $data = [
            'productId' => 1,
            'userId' => 1,
            'sessionId' => uniqid(),
            'quantity' => 5,
        ];

        $cartTableGatewayMock = $this->getMockBuilder('\Core42\Db\TableGateway\AbstractTableGateway')
            ->disableOriginalConstructor()
            ->getMock();
        $cartTableGatewayMock->method('insert')
            ->will($this->returnCallback(function($set){
                $set->setId(mt_rand(5, 100));

                return $set;
            }));
        $cartTableGatewayMock->method('getModel')
            ->willReturn(new GenericModel());

        $resultSet = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->getMock();
        $resultSet->method("count")
            ->willReturn(0);
        $cartTableGatewayMock->method('select')
            ->willReturn($resultSet);

        $this->serviceManager->get('TableGateway')->setService(CartTableGatewayInterface::class, $cartTableGatewayMock);

        $productTableGatewayMock = $this->getMockBuilder('\Core42\Db\TableGateway\AbstractTableGateway')
            ->disableOriginalConstructor()
            ->getMock();

        $productModel = $this->getMockBuilder(ProductTableGatewayInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productTableGatewayMock->method('selectByPrimary')
            ->will($this->returnCallback(function($id) use ($productModel){
                $productModel->method('getId')
                    ->willReturn($id);

                $productModel->method('getStatus')
                    ->willReturn(ProductInterface::STATUS_ACTIVE);

                return $productModel;
            }));
        $this->serviceManager->get('TableGateway')->setService(
            ProductTableGatewayInterface::class,
            $productTableGatewayMock
        );

        $cmd = new CartUpdateCommand($this->serviceManager);

        $cmd->setProductId($data['productId'])
            ->setUserId($data['userId'])
            ->setSessionId($data['sessionId'])
            ->setQuantity($data['quantity']);

        $result = $cmd->run();

        $this->assertEquals($data['productId'], $result->getProductId());
        $this->assertEquals($data['sessionId'], $result->getSessionId());
        $this->assertEquals($data['userId'], $result->getUserId());
        $this->assertEquals($data['quantity'], $result->getQuantity());
        $this->assertGreaterThan(0, $result->getId());
    }
}
