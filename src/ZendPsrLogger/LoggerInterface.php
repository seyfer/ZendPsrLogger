<?php

namespace ZendPsrLogger;

use Psr\Log\LoggerInterface as PsrLoggerInterface;
use Zend\Log\LoggerInterface as ZendLoggerInterface;

/**
 *
 * @author seyfer
 */
interface LoggerInterface extends PsrLoggerInterface
{

    /**
     * @return array
     */
    public function getExtra();

    /**
     * @param array $extra
     */
    public function addExtra(array $extra = array());
}
