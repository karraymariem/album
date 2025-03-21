<?php

namespace Album\Model;

use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;

class AlbumTableGateway
{
    protected $tableGateway;

    public function __construct(Adapter $adapter)
    {
        // Create a TableGateway instance for the 'album' table
        $this->tableGateway = new TableGateway('album', $adapter);
    }

    public function insertAlbum($data)
    {
        $this->tableGateway->insert($data);
        return $this->tableGateway->lastInsertValue; // Returns the last inserted ID
    }

    public function selectAll()
    {
        // Select all albums from the table
        return $this->tableGateway->select();
    }

    public function fetchAlbumById($id)
    {
        // Fetch album by id
        return $this->tableGateway->select(['id' => $id])->current();
    }

    public function updateAlbum($id, $data)
    {
        // Update album data based on the ID
        return $this->tableGateway->update($data, ['id' => $id]);
    }

    public function deleteAlbum($id)
    {
        // Delete album by ID
        return $this->tableGateway->delete(['id' => $id]);
    }
}
