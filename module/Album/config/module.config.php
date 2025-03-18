<?php

namespace Album;

use Album\Controller\AlbumController;
use Album\Model\AlbumTable;
use Album\Model\AlbumTableFactory;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

return [
    // Controllers configuration
    'controllers' => [
        'factories' => [
            AlbumController::class => ReflectionBasedAbstractFactory::class,
        ],
    ],

    // Routing configuration
    'router' => [
        'routes' => [
            'album' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/album[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => AlbumController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    // View configuration
    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],

    // Service manager configuration
    'service_manager' => [
        'factories' => [
            AlbumTable::class => AlbumTableFactory::class,
        ],
    ],
];
