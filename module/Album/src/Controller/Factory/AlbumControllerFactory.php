<?php 
namespace Album\Controller\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Album\Controller\AlbumController;
use Album\Model\AlbumTable;

class AlbumControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        // Get the AlbumTable service from the container
        $albumTable = $container->get(AlbumTable::class);

        // Create a new instance of AlbumController
        $controller = new AlbumController();
        $controller->setAlbumTable($albumTable); // Set the albumTable via setter

        // Return the controller
        return $controller;
    }
}
