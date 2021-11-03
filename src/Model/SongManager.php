<?php

namespace App\Model;

class SongManager extends AbstractManager
{
    public const TABLE = 'song';

    public function selectAllByUserId($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM song s WHERE s.user_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
