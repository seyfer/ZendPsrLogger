<?php

namespace ZendPsrLogger\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use ZendPsrLogger\Service\LoggerFactory;
use Doctrine\ORM\EntityManager;
use ZendPsrLogger\Doctrine\ORM\EntityManagerHelper;

/**
 * Description of AbstractLoggerFactory
 *
 * @author seyfer
 */
class AbstractLoggerFactory implements AbstractFactoryInterface
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

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (strpos($requestedName, 'Logger') !== FALSE) {

            $config = $serviceLocator->get('config');

            if (!isset($config['logger']) ||
                    !isset($config['logger']['registeredLoggers'][$requestedName])) {
                throw new ServiceNotFoundException(__METHOD__ . " you need add "
                . "logger => registegedLoggers => $requestedName to your config");
            }

            $entityName = $config['logger']['registeredLoggers'][$requestedName]['entityClassName'];
            $em         = $this->getEM($serviceLocator);

            if (!EntityManagerHelper::isEntity($em, $entityName)) {
                throw new ServiceNotFoundException(__METHOD__ . " you need set valid "
                . " mapped entity class name in $requestedName => entityClassName");
            }

            return TRUE;
        }

        return FALSE;
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $factory = new LoggerFactory();
        return $factory->create($serviceLocator, $requestedName);
    }

}
