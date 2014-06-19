<?php

return array(
    'doctrine'      => array(
        'driver' => array(
            'ZendPsrLogger_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/ZendPsrLogger/Entity')
            ),
            'orm_default'            => array(
                'drivers' => array(
                    'ZendPsrLogger\Entity' => 'ZendPsrLogger_entities'
                )
            )
        ),
    ),
    'logger'        => array(
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
    'module_config' => array(
    ),
    'router'        => array(
        'routes' => array(
        ),
    ),
    'console'       => array(
        'router' => array(
            'routes' => array(
            )
        )
    ),
    'controllers'   => array(
        'invokables' => array(
        ),
    ),
    'view_manager'  => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_path_stack'      => array(
            __DIR__ . '/../view',
        ),
    ),
);
