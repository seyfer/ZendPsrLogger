ZendPsrLogger
=============

This is module that implement PSR-3 log interface with Zend\log component.
Also implement Doctrine log writer.

Installation
------------

Get from packagist or github.

`"seyfer/zend-psr-logger": "dev-master"`

[[http://modules.zendframework.com/seyfer/ZendPsrLogger]]

If using Zend2, than enable in application.config.php,
add 'ZendPsrLogger' to modules.

Configuration
-------------

Extra column mapping and Entity name in module.config.php.
Extra column `fileName` for example.

```
'logger'        => array(
            'entityClassName' => 'MyModule\Entity\Log\MyModuleLog',
            'columnMap'       => array(
                'timestamp'    => 'timestamp',
                'priority'     => 'priority',
                'priorityName' => 'priorityName',
                'message'      => 'message',
                'extra'        => array(
                    'fileName' => 'fileName',
            ),
        )
    ),
```

Than set factory with your module logger custom name in Module.php

```
public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'NameLogger' => 'Logger\Service\LoggerFactory',
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

Usage
-----

First init logger

```
if ($this->getServiceLocator()->has($myLoggerName)) {
    $logger = $this->getServiceLocator()->get($myLoggerName);
} else {
    $logger = new \Logger\NullLogger();
}

$this->logger = $logger;
```

Than use it's level function

```
$this->logger->addExtra(['fileName' => 'test.txt']);
$this->logger->debug("test");
```

Or log directly using \Psr\Log\LogLevel constants for level parameter.

```
$this->logger->log(\Psr\Log\LogLevel::DEBUG, "test", ['fileName' => 'test.txt']);
```

License
-------

GPL. If you want implement more writers or other improvements - you're welcome!