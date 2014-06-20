<?php

namespace ZendPsrLogger\Writer;

use Zend\Log\Formatter\Db as DbFormatter;
use Zend\Log\Writer\AbstractWriter;
use ZendPsrLogger\Entity\BaseLog;
use Doctrine\ORM\EntityManager;

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
     * @param array $columnMap
     * @return void
     */
    public function __construct($modelClass, EntityManager $entityManager, $columnMap = null)
    {
        if (!$modelClass || !class_exists($modelClass)) {
            throw new \RuntimeException(__METHOD__ . " you need use entity name "
            . "as param");
        }

        $this->em         = $entityManager;
        $this->columnMap  = $columnMap;
        $this->modelClass = $modelClass;

        if (!$this->hasFormatter()) {
            $this->setFormatter(new DbFormatter());
        }
    }

    protected function doWrite(array $event)
    {
        $this->logEntity = new $this->modelClass();
        $this->logEntity->exchangeArray($event);

        if ($event['extra']) {
            $this->logEntity->exchangeArray($event['extra']);
        }

        $this->em->persist($this->logEntity);
        $this->em->flush();
    }

}
