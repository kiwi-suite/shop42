<?php
namespace Shop42\TableGateway\Service;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TableGatewayFactory implements FactoryInterface
{

    /**
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     * @throws \Exception
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $sm = $container->getServiceLocator();
        $config = $sm->get('config')['shop42']['table_gateway'];
        if (empty($config[$requestedName])) {
            throw new \Exception(sprintf("'%s' not set or invalid", $requestedName));
        }

        return $container->get($config[$requestedName]);
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null)
    {
        return $this($serviceLocator, $requestedName);
    }
}
