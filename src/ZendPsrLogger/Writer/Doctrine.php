<?php

namespace ZendPsrLogger\Writer;

use Doctrine\ORM\EntityManager;
use Zend\Log\Formatter\Db as DbFormatter;
use Zend\Log\Writer\AbstractWriter;
use ZendPsrLogger\Entity\BaseLog;

/**
 * Description of Doctrine
 *
 * @author seyfer
 */
class Doctrine extends AbstractWriter
{

    /**
     * @var null|array
     */
    private $columnMap = null;

    /**
     * @var string
     */
    private $modelClass = null;

    /**
     *
     * @var BaseLog
     */
    private $logEntity;

    /**
     *
     * @var EntityManager
     */
    private $em;

    /**
     * Constructor
     *
     * @param string $modelClass
     * @param EntityManager $entityManager
     * @param array $columnMap
     */
    public function __construct($modelClass, EntityManager $entityManager, $columnMap = null)
    {
        if (!$modelClass || !class_exists($modelClass)) {
            throw new \RuntimeException(__METHOD__ . " you need use entity name " . "as param");
        }

        $this->em = $entityManager;
        $this->columnMap = $columnMap;
        $this->modelClass = $modelClass;

        if (!$this->hasFormatter()) {
            $this->setFormatter(new DbFormatter());
        }
    }

    /**
     * @param array $event
     */
    protected function doWrite(array $event)
    {
        $this->logEntity = new $this->modelClass();
        $this->logEntity->exchangeArray($event);

        if ($event['extra']) {
            $this->logEntity->exchangeArray($event['extra']);
        }

        try {
            $this->checkEMConnection();

            $this->em->persist($this->logEntity);
            $this->em->flush();
        } catch (\Exception $e) {
            //reconnect on exeption
            $this->checkEMConnection();
        }
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    /**
     * reconnect
     */
    protected function checkEMConnection()
    {
        if (!$this->getEntityManager()->isOpen()) {
            $connection = $this->getEntityManager()->getConnection();
            $config = $this->getEntityManager()->getConfiguration();

            $this->em = $this->getEntityManager()->create(
                $connection, $config
            );
        }
    }

}
