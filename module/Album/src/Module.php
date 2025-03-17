<?php
namespace Album;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;
use Album\Model\AlbumTable;
use Album\Model\Album;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                AlbumTable::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new AlbumTable(new TableGateway('album', $dbAdapter));
                },
            ],
        ];
    }
}
