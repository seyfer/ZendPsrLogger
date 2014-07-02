<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendPsrLogger;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface,
    Zend\ModuleManager\Feature\DependencyIndicatorInterface,
    Zend\Console\Adapter\AdapterInterface as Console;
use ZendPsrLogger\Doctrine\ORM\EntityManagerHelper;

class Module implements ConsoleUsageProviderInterface, DependencyIndicatorInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $this->addDefaultLogExtraIfValid($e);
    }

    private function addDefaultLogExtraIfValid(MvcEvent $e)
    {
        $request = $e->getParam('request');
        if (!$request instanceof \Zend\Console\Request) {

            $sm = $e->getApplication()->getServiceManager();

            $entityName = $this->getDefaultLogEntityName($sm);

            $em = $sm->get("Doctrine\ORM\EntityManager");
            if ($entityName && EntityManagerHelper::isEntity($em, $entityName)) {
                $servParam  = $request->getServer();
                $remoteAddr = $servParam->get('REMOTE_ADDR');

                $e->getApplication()->getServiceManager()
                        ->get('DefaultLogger')
                        ->addExtra(array(
                            'ipaddress' => $remoteAddr,
                                )
                );
            }
        }
    }

    private function getDefaultLogEntityName($sm)
    {
        $config           = $sm->get('config');
        $defaultLogConfig = $config['logger']['registeredLoggers']['DefaultLogger'];

        if (isset($defaultLogConfig)) {
            $entityName = $defaultLogConfig['entityClassName'];
        }

        return $entityName;
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(
                'DefaultLogger' => '\ZendPsrLogger\Service\AbstractLoggerFactory',
            ),
            'shared'             => array(
                'doctrine.entitymanager.orm_default' => false
            ),
        );
    }

    public function getConsoleUsage(Console $console)
    {
        return array(
        );
    }

    public function getModuleDependencies()
    {
        return array(
            'DoctrineModule',
            'DoctrineORMModule',
        );
    }

}
