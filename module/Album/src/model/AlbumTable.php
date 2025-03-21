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

    // Fetch a single album by its code
    public function getAlbum($code)
    {
        $result = $this->tableGateway->select(['code' => $code]);
        return $result->current();
    }

    // Insert a new album
    public function saveAlbum($data)
    {
        $albumData = [
            'code' => $data['code'],
            'designation' => $data['designation'],
            'cadence' => $data['cadence'],
            'description' => $data['description']
        ];

        try {
            $this->tableGateway->insert($albumData);
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to insert album: ' . $e->getMessage());
        }
    }


    // Delete an album by its code
    public function deleteAlbum($code)
    {
        $this->tableGateway->delete(['code' => $code]);
    }
}
