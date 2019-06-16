<?php

return [
    'doctrine' => [
        'driver' => [
            'ZendPsrLogger_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/ZendPsrLogger/Entity'],
            ],
            'orm_default' => [
                'drivers' => [
                    'ZendPsrLogger\Entity' => 'ZendPsrLogger_entities',
                ],
            ],
        ],
    ],
    'logger' => [
        'registeredLoggers' => [
            'DefaultLogger' => [
                'entityClassName' => '\ZendPsrLogger\Entity\DefaultLog',
                'columnMap' => [
                    'timestamp' => 'timestamp',
                    'priority' => 'priority',
                    'priorityName' => 'priorityName',
                    'message' => 'message',
                    'extra' => [
                        'ipaddress' => 'ipaddress',
                    ],
                ],
            ],
        ],
    ],
    'module_config' => [
    ],
    'router' => [
        'routes' => [
        ],
    ],
    'console' => [
        'router' => [
            'routes' => [
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
