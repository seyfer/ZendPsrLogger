<?php

namespace ZendPsrLogger;

use Psr\Log\LogLevel;
use Zend\Log\Logger as ZendLogger;
use Zend\Log\LoggerInterface as ZendLoggerInterface;

/**
 * Description of Logger
 *
 * @author seyfer
 */
class Logger implements LoggerInterface
{

    /**
     * extra column values
     * @var array
     */
    private $extra = [];

    /**
     * zend logger implementation
     * @var ZendLoggerInterface
     */
    private $externalLogger;

    /**
     * map from psr log levels
     * to zend log priorities for compatibility
     * @var
     */
    private $psrToZendPriorityMap = [
        LogLevel::ALERT => ZendLogger::ALERT,
        LogLevel::CRITICAL => ZendLogger::CRIT,
        LogLevel::DEBUG => ZendLogger::DEBUG,
        LogLevel::EMERGENCY => ZendLogger::EMERG,
        LogLevel::ERROR => ZendLogger::ERR,
        LogLevel::INFO => ZendLogger::INFO,
        LogLevel::NOTICE => ZendLogger::NOTICE,
        LogLevel::WARNING => ZendLogger::WARN,
    ];

    /**
     *
     * @param array $extra
     * @param \Zend\Log\LoggerInterface|null $externalLogger
     * @throws \RuntimeException
     */
    public function __construct(array $extra = [], $externalLogger = null)
    {
        $this->extra = $extra;

        if (!$externalLogger) {
            $this->externalLogger = new ZendLogger();
        } else if ($externalLogger instanceof ZendLoggerInterface) {
            $this->externalLogger = $externalLogger;
        } else {
            throw new \RuntimeException(__METHOD__ . " you must set log class "
                . "that implement Zend\\Log\\LoggerInterface");
        }
    }

    /**
     * get setted extra
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * set extra values
     * usage: addExtra(['extraName' => 'extraValue']);
     * @param array $extra
     */
    public function addExtra(array $extra = [])
    {
        if (count($this->getExtra()) > 0) {
            $this->extra = array_merge($this->getExtra(), $extra);
        } else {
            $this->extra = $extra;
        }
    }

    /**
     * addExtra alias
     * @param array $extra
     */
    public function setExtra(array $extra = [])
    {
        $this->addExtra($extra);
    }

    /**
     * main log function
     * @param $level
     * @param $message
     * @param array $context
     * @return null|void
     */
    public function log($level, $message, array $context = [])
    {
        if (isset($this->psrToZendPriorityMap[$level])) {
            $level = $this->psrToZendPriorityMap[$level];
        }


        $this->externalLogger
            ->log($level, $message, $this->getExtraWithContextMerged($context));
    }

    /**
     * merge extra with current context
     * @param array $context
     * @return array
     */
    private function getExtraWithContextMerged(array $context = [])
    {
        $extra = $this->getExtra();
        if (!empty($context)) {
            $extra = array_merge($extra, $context);
        }

        return $extra;
    }

    public function alert($message, array $context = [])
    {
        $this->externalLogger->alert($message, $this->getExtraWithContextMerged($context));
    }

    public function critical($message, array $context = [])
    {
        $this->externalLogger->crit($message, $this->getExtraWithContextMerged($context));
    }

    public function emergency($message, array $context = [])
    {
        $this->externalLogger->emerg($message, $this->getExtraWithContextMerged($context));
    }

    public function error($message, array $context = [])
    {
        $this->externalLogger->err($message, $this->getExtraWithContextMerged($context));
    }

    public function warning($message, array $context = [])
    {
        $this->externalLogger->warn($message, $this->getExtraWithContextMerged($context));
    }

    public function debug($message, array $context = [])
    {
        $this->externalLogger->debug($message, $this->getExtraWithContextMerged($context));
    }

    public function info($message, array $context = [])
    {
        $this->externalLogger->info($message, $this->getExtraWithContextMerged($context));
    }

    public function notice($message, array $context = [])
    {
        $this->externalLogger->notice($message, $this->getExtraWithContextMerged($context));
    }

    /**
     * call for other methods
     * @param $name
     * @param $arguments
     * @throws \BadMethodCallException
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this->externalLogger, $name)) {
            \call_user_func_array([$this->externalLogger, $name], $arguments);
        } else {
            throw new \BadMethodCallException(__METHOD__ . " method " .
                $name . " does not exists");
        }
    }

}
