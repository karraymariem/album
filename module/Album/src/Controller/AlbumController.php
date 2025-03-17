<?php 
namespace Album\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Album\Model\AlbumTable;
use Album\Form\AlbumForm;
use Album\Model\Album;
use Laminas\Http\Request;


class AlbumController extends AbstractActionController
{
    // Declare the albumTable property to be set by the service manager
    protected $albumTable;

    // Setter method to inject the AlbumTable dependency
    public function setAlbumTable(AlbumTable $albumTable)
    {
        $this->albumTable = $albumTable;
    }

    public function indexAction()
    {
        return new ViewModel([
            'albums' => $this->albumTable->fetchAll(),
        ]);
    }

    public function addAction()
    {
    }

    public function editAction()
    {
        
    }
}

