<?php
namespace Album;

use Album\Model\AlbumTable;  // Added the correct use statement

return [
    'controllers' => [

    
        'factories' => [
            Controller\AlbumController::class => function ($container) {
                $controller = new Controller\AlbumController();
                $albumTable = $container->get(AlbumTable::class);  // Corrected to use the fully qualified name
                $controller->setAlbumTable($albumTable); // Inject AlbumTable
                return $controller;
            },
        ],
    ],
    'router' => [
        'routes' => [
            'album' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/album',
                    'defaults' => [
                        'controller' => Controller\AlbumController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'add' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/add',
                            'defaults' => [
                                'controller' => Controller\AlbumController::class,
                                'action'     => 'add',
                            ],
                        ],
                    ],
                    'edit' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/edit[/:id]',
                            'defaults' => [
                                'controller' => Controller\AlbumController::class,
                                'action'     => 'edit',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/delete[/:id]',
                            'defaults' => [
                                'controller' => Controller\AlbumController::class,
                                'action'     => 'delete',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_map' => [
            // Map the view to the correct file path
            'album/album/index' => __DIR__ . '/../view/album/album/index.phtml',
        ],
        'template_path_stack' => [
            'album' => __DIR__ . '/../view',  // The default path to your Album module's views
        ],
    ],
];
