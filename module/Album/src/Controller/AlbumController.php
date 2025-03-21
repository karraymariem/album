<?php

namespace Album\Controller;

use Album\Form\AlbumForm;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AlbumController extends AbstractActionController
{
    private $albumTable;

    public function __construct(AlbumTable $albumTable)
    {
        $this->albumTable = $albumTable;
    }

    // Display all albums
    public function indexAction()
    {
        $albums = $this->albumTable->fetchAll();
        return new ViewModel([
            'albums' => $albums,
        ]);
    }

    // Add a new album
    public function addAction()
    {
        $request = $this->getRequest();

        // Check if it's an AJAX request
        if ($request->isXmlHttpRequest()) {
            $data = $request->getPost();  // Get form data from the POST request

            // Validate the data (you can add custom validation logic here if needed)

            $album = new Album();
            $album->exchangeArray($data);  // Fill the album model with form data

            try {
                $this->albumTable->saveAlbum($album);  // Save the album to the database
                $response = ['success' => true, 'message' => 'Album added successfully.'];
            } catch (\Exception $e) {
                $response = ['success' => false, 'message' => 'Failed to add album.'];
            }

            // Return the response as JSON
            return new \Laminas\View\Model\JsonModel($response);
        }

        // Fallback if it's not an AJAX request (you can add code here for normal rendering)
        return new ViewModel();
    }
}
