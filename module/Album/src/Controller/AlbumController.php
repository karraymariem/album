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
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        
        $request = $this->getRequest();

        
        if (!$request instanceof Request) {
            throw new \Exception('Expected a Request object');
        }

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());
        $this->albumTable->saveAlbum($album); // Correct usage of albumTable
        return $this->redirect()->toRoute('album');
    }

    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('album', ['action' => 'add']);
        }

        
        try {
            $album = $this->albumTable->getAlbum($id); // Use albumTable, not table
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (!$request instanceof Request) {
            throw new \Exception('Expected a Request object');
        }

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->albumTable->saveAlbum($album); // Use albumTable, not table
        } catch (\Exception $e) {
            // Handle exception
        }

        // Redirect to album list
        return $this->redirect()->toRoute('album', ['action' => 'index']);
    }

    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if ($id) {
            $this->albumTable->deleteAlbum($id); // Use albumTable, not table
        }
        return $this->redirect()->toRoute('album'); // Redirect to album list
    }
}

