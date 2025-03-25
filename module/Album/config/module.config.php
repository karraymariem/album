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
            'album_ajax' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/album/add',
                    'defaults' => [
                        'controller' => AlbumController::class,
                        'action'     => 'add',
                    ],
                ],
            ],

            'album_edit' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/album/edit',
                    'defaults' => [
                        'controller' => AlbumController::class,
                        'action'     => 'edit',
                    ],
                ],
            ],

            'album_delete' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/album/delete',
                    'defaults' => [
                        'controller' => AlbumController::class,
                        'action'     => 'delete',
                    ],
                ],
            ],
            'album_duplicate' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/album/duplicate',
                    'defaults' => [
                        'controller' => AlbumController::class,
                        'action'     => 'duplicate',
                    ],
                ],
            ],

        ],
    ],


    'view_manager' => [
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',
        ],
    ],


    'service_manager' => [
        'factories' => [
            AlbumTable::class => AlbumTableFactory::class,

        ],

    ],
];
