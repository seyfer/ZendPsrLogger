ZendPsrLogger
=============

This is module that implement PSR-3 log interface with Zend\log component.
Also implement Doctrine log writer.

Installation
------------

Get from packagist or github.

`require "seyfer/zend-psr-logger": "dev-master"`

http://modules.zendframework.com/seyfer/ZendPsrLogger

If using Zend2, than enable in application.config.php,
add 'ZendPsrLogger' to modules.

Configuration
-------------

Extra column mapping and Entity name in module.config.php.
Extra column `ipaddress` for example.

```
'logger'        => array(
        'registeredLoggers' => array(
            'DefaultLogger' => array(
                'entityClassName' => '\ZendPsrLogger\Entity\DefaultLog',
                'columnMap'       => array(
                    'timestamp'    => 'timestamp',
                    'priority'     => 'priority',
                    'priorityName' => 'priorityName',
                    'message'      => 'message',
                    'extra'        => array(
                        'ipaddress' => 'ipaddress',
                    ),
                )
            ),
            'ElseDefaultLogger' => array(
                'entityClassName' => '\ZendPsrLogger\Entity\ElseDefaultLog',
                'columnMap'       => array(
                    'timestamp'    => 'timestamp',
                    'priority'     => 'priority',
                    'priorityName' => 'priorityName',
                    'message'      => 'message',
                    'extra'        => array(
                        'fileName' => 'fileName',
                    ),
                )
            )
        ),
    ),
```

Than set abstract factory with your module logger custom name in Module.php

```
public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(
                'DefaultLogger' => '\ZendPsrLogger\Service\AbstractLoggerFactory',
                'ElseDefaultLogger' => '\ZendPsrLogger\Service\AbstractLoggerFactory',
            ),
        );
    }
```

Factory will use your module.config.php `logger` config.
You can implement your factory and using writers,
that implement Zend\Log\Writer\AbstractWriter.

To implement your Doctrine log entity you can extend it from
`Logger\Entity\BaseLog` and just add your extra column mapping.

```
<?php

namespace MyModule\Entity\Log;

use Doctrine\ORM\Mapping as ORM;
use Logger\Entity\BaseLog;

/**
 * @ORM\Entity
 * @ORM\Table(name="parser_log")
 */
class MyModuleLog extends BaseLog
{

    /**
     * @var string
     * @ORM\Column(type="string", name="file_name", nullable=true)
     */
    protected $fileName;

}

```

And than register your logger in your Module and add config like in example.


Usage
-----

First init logger

```
use ZendPsrLogger\LoggerInterface;
use ZendPsrLogger\NullLogger;
...

/** @var type LoggerInterface */
$this->logger
...

if ($this->getServiceLocator()->has($myLoggerName)) {
    $logger = $this->getServiceLocator()->get($myLoggerName);
} else {
    $logger = new NullLogger();
}

$this->logger = $logger;
```

Than use it's level function

```
$this->logger->addExtra(['ipaddress' => '11.22.33.44']);
$this->logger->debug("test");
```

Or log directly using \Psr\Log\LogLevel constants for level parameter.

```
$this->logger->log(\Psr\Log\LogLevel::DEBUG, "test", ['ipaddress' => '11.22.33.44']);
```

License
-------

GPL. If you want implement more writers or other improvements - you're welcome!

Change Log
----------

v1.1.0
- use AbstractLoggerFactory and new config structure for multiple logger creation

v1.0.1
- implement Doctrine writer
- use LoggerFactory for logger creation