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
     * @param type $level
     * @param type $message
     * @param type $context
     * @return null
     */
    public function log($level, $message, $context = array())
    {
        parent::log($level, $message, $context);
    }

}
