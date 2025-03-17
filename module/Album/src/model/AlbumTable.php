<?php 
namespace Album\Model;

use RuntimeException;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Album\Model\Album; // Ensure that you import the Album class

class AlbumTable
{
    private $tableGateway;

    // Constructor to inject TableGatewayInterface dependency
    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    // Fetch all albums from the table
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    // Fetch a specific album by its ID
    public function getAlbum($id)
    {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(['id' => $id]);

        // Convert the rowset to an array
        $row = iterator_to_array($rowset);

        // If no rows found, throw an exception
        if (!$row) {
            throw new RuntimeException(sprintf(
                'Could not find row with identifier %d',
                $id
            ));
        }

        return $row[0]; // Return the first row from the array
    }

    // Save album data to the database (insert or update)
    public function saveAlbum(Album $album)
    {
        $data = [
            'artist' => $album->artist,
            'title'  => $album->title,
        ];

        $id = $album->id; // No need to cast to int if it's already an integer or null

        if (!$id) {
            // Insert a new album if ID is not set
            $this->tableGateway->insert($data);
            return;
        }

        // Try to fetch the album by its ID to check if it exists
        try {
            $this->getAlbum($id);
        } catch (RuntimeException $e) {
            throw new RuntimeException(sprintf(
                'Cannot update album with identifier %d; does not exist',
                $id
            ));
        }

        // Update the album if it exists
        $this->tableGateway->update($data, ['id' => $id]);
    }

    // Delete an album by its ID
    public function deleteAlbum($id)
    {
        $this->tableGateway->delete(['id' => (int) $id]);
    }
}
