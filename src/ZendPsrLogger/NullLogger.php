<?php

namespace ZendPsrLogger;

use Psr\Log\NullLogger as PsrNullLogger;

/**
 * Description of NullLogger
 *
 * @author seyfer
 */
class NullLogger extends PsrNullLogger
{

    /**
     * logs to null
     * @param $level
     * @param $message
     * @param $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        parent::log($level, $message, $context);
    }

}
