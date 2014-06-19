<?php

namespace ZendPsrLogger\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LoggerFactory
 *
 * @author seyfer
 */
class LoggerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
//        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        $config     = $serviceLocator->get('config');
        $entityName = $config['logger']['entityClassName'];
        $columnMap  = $config['logger']['columnMap'];

        $logger = new \ZendPsrLogger\Logger();
        $writer = new \ZendPsrLogger\Writer\Doctrine($entityName, $columnMap);

        $logger->addWriter($writer);
        return $logger;
    }

}
