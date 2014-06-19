<?php

namespace ZendPsrLogger\Writer;

use Zend\Log\Formatter\Db as DbFormatter;
use Zend\Log\Writer\AbstractWriter;

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
     * Constructor
     *
     * @param string $modelClass
     * @param array $columnMap
     * @return void
     */
    public function __construct($modelClass, $columnMap = null)
    {
        if (!$modelClass || !class_exists($modelClass)) {
            throw new \RuntimeException(__METHOD__ . " you need use entity name "
            . "as param");
        }

        $this->columnMap  = $columnMap;
        $this->modelClass = $modelClass;

        if (!$this->hasFormatter()) {
            $this->setFormatter(new DbFormatter());
        }
    }

    protected function doWrite(array $event)
    {
        \Zend\Debug\Debug::dump($event);

//        $event = $this->formatter->format($event);

        \Zend\Debug\Debug::dump($this->columnMap);
        \Zend\Debug\Debug::dump($this->modelClass);

        exit();
    }

}
