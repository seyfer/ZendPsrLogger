<?php

namespace ZendPsrLogger;

use Psr\Log\LoggerInterface as PsrLoggerInterface;

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
    public function addExtra(array $extra = []);
}
