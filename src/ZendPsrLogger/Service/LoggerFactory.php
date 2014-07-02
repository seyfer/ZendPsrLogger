<?php

namespace ZendPsrLogger\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;

/**
 * Description of LoggerFactory
 *
 * @author seyfer
 */
class LoggerFactory implements FactoryInterface
{

    /**
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return EntityManager
     */
    protected function getEM(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        return $entityManager;
    }

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this->create($serviceLocator, "DefaultLogger");
    }

    public function create(ServiceLocatorInterface $serviceLocator, $requestedName)
    {
        $entityManager = $this->getEM($serviceLocator);

        $config     = $serviceLocator->get('config');
        $entityName = $config['logger']['registeredLoggers'][$requestedName]['entityClassName'];
        $columnMap  = $config['logger']['registeredLoggers'][$requestedName]['columnMap'];

        $logger = new \ZendPsrLogger\Logger();
        $writer = new \ZendPsrLogger\Writer\Doctrine($entityName, $entityManager, $columnMap);

        $logger->addWriter($writer);

        return $logger;
    }

}
