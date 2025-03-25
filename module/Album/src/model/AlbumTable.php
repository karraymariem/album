<?php

namespace Album\Model;

use Laminas\Db\TableGateway\TableGatewayInterface;

class AlbumTable
{
    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    // Fetch all albums
    public function fetchAll()
    {
        return $this->tableGateway->select();
    }

    public function saveAlbum($code, $designation, $description)
    {
        $data = [
            'code' => $code,
            'designation' => $designation,
            'description' => $description,
        ];

        error_log(print_r($data, true));
        return $this->tableGateway->insert($data);
    }

    public function getAlbumByCode($code)
    {
        $rowset = $this->tableGateway->select(['code' => $code]);
        return $rowset->current();
    }

    public function updateAlbumByCode($code, $designation, $description)
    {
        return $this->tableGateway->update(
            ['designation' => $designation, 'description' => $description],
            ['code' => $code]
        );
    }

    public function deleteAlbumByCode($code)
    {
        return $this->tableGateway->delete(['code' => $code]);
    }

    // Delete an album
    public function deleteAlbum($code)
    {
        try {
            $where = ['code' => $code];
            $result = $this->tableGateway->delete($where);

            if ($result === 0) {
                throw new \Exception("No album found with the code: " . $code);
            }

            return true;
        } catch (\Exception $e) {
            error_log('Error deleting album: ' . $e->getMessage());
            return false;
        }
    }

    // Duplicate an album
    public function duplicateAlbum($code)
    {
        // Fetch the album by code
        $result = $this->tableGateway->select(['code' => $code]);
        $album = $result->current();

        // Check if album exists
        if (!$album) {
            throw new \Exception("Album with code $code not found");
        }
        $newCode = $album->code . '_copie';

        // Prepare the data for the new duplicated album
        $data = [
            'code' => $newCode,            // New unique code
            'designation' => $album->designation,
            'cadence' => $album->cadence,
            'description' => $album->description
        ];

        // Insert the duplicated album into the database
        return $this->tableGateway->insert($data);
    }
}
