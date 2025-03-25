<?php

namespace Album\Controller;

use Album\Form\AlbumForm;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Laminas\Db\Sql\Delete;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;

use Laminas\Http\Request;

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

    public function addAction()
    {
        $request = $this->getRequest();

        // Ensure the response is JSON
        $this->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'application/json');

        if (!$request->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method. Must be POST.'
            ]);
            return $this->getResponse();
        }

        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                'success' => false,
                'message' => 'JSON decode error: ' . json_last_error_msg()
            ]);
            return $this->getResponse();
        }

        if (isset($data['action']) && $data['action'] === 'add') {
            $code = $data['code'] ?? '';
            $designation = $data['designation'] ?? '';
            $description = $data['description'] ?? '';

            try {
                $result = $this->albumTable->saveAlbum($code, $designation, $description);
            } catch (\Exception $e) {
                error_log("Database insert error: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ]);
                return $this->getResponse();
            }

            echo json_encode([
                'success' => $result ? true : false,
                'message' => $result ? 'Album added successfully' : 'Failed to add album'
            ]);
            return $this->getResponse();
        }

        echo json_encode([
            'success' => false,
            'message' => 'Invalid action'
        ]);
        return $this->getResponse();
    }


    public function editAction()
    {
        $request = $this->getRequest();

        if ($request->isPost()) {
            try {
                // Get JSON data from the request body
                $data = json_decode(file_get_contents("php://input"), true);

                if (!$data) {
                    throw new \Exception("Invalid or empty request body");
                }

                if (isset($data['action']) && $data['action'] === 'edit') {
                    $code = $data['code'] ?? '';
                    $designation = $data['designation'] ?? '';
                    $description = $data['description'] ?? '';

                    if (empty($code)) {
                        throw new \Exception("Album code is required for update.");
                    }

                    // Fetch the album by code
                    $album = $this->albumTable->getAlbumByCode($code);
                    if (!$album) {
                        throw new \Exception("No album found with code: $code");
                    }

                    // Perform the update using the fetched album data
                    $result = $this->albumTable->updateAlbumByCode($code, $designation, $description);

                    if (!$result) {
                        throw new \Exception("Failed to update album with code: $code");
                    }

                    echo json_encode(['success' => true, 'message' => 'Album updated successfully']);
                } else {
                    throw new \Exception("Invalid action: " . $data['action']);
                }
            } catch (\Exception $e) {
                error_log("Error updating album: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }

        return $this->getResponse();
    }
    public function deleteAction()
    {
        $request = $this->getRequest();

        // Ensure JSON response
        $this->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'application/json');

        if (!$request->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method. Must be POST.'
            ]);
            return $this->getResponse();
        }

        // Get raw POST data
        $input = file_get_contents("php://input");
        $data = json_decode($input, true); // Decode the JSON body

        // Log the data to check if "code" is being received correctly
        error_log("Received data: " . print_r($data, true));

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                'success' => false,
                'message' => 'JSON decode error: ' . json_last_error_msg()
            ]);
            return $this->getResponse();
        }

        if (isset($data['code']) && !empty($data['code'])) {
            $code = $data['code']; // Get the code from the POST data

            try {
                $result = $this->albumTable->deleteAlbumByCode($code); // Use the code to delete

                echo json_encode([
                    'success' => $result ? true : false,
                    'message' => $result ? 'Album deleted successfully' : 'Failed to delete album'
                ]);
            } catch (\Exception $e) {
                error_log("Error deleting album: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => 'Error deleting album: ' . $e->getMessage()
                ]);
            }

            return $this->getResponse();
        }

        echo json_encode([
            'success' => false,
            'message' => 'Album code is missing for deletion.'
        ]);
        return $this->getResponse();
    }
    public function duplicateAction()
    {
        $request = $this->getRequest();

        // Ensure the response is JSON
        $this->getResponse()->getHeaders()->addHeaderLine('Content-Type', 'application/json');

        if (!$request->isPost()) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method. Must be POST.'
            ]);
            return $this->getResponse();
        }

        // Get raw POST data
        $input = file_get_contents("php://input");
        $data = json_decode($input, true); // Decode the JSON body

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                'success' => false,
                'message' => 'JSON decode error: ' . json_last_error_msg()
            ]);
            return $this->getResponse();
        }

        // Validate and get code
        if (isset($data['code']) && !empty($data['code'])) {
            $code = $data['code'];

            try {
                $result = $this->albumTable->duplicateAlbum($code);

                echo json_encode([
                    'success' => $result ? true : false,
                    'message' => $result ? 'Album duplicated successfully' : 'Failed to duplicate album'
                ]);
            } catch (\Exception $e) {
                error_log("Error duplicating album: " . $e->getMessage());
                echo json_encode([
                    'success' => false,
                    'message' => 'Error duplicating album: ' . $e->getMessage()
                ]);
            }

            return $this->getResponse();
        }

        echo json_encode([
            'success' => false,
            'message' => 'Album code is missing for duplication.'
        ]);
        return $this->getResponse();
    }
}
