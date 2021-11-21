<?php

namespace App\Model;

class RatingManager extends AbstractManager
{
    public const TABLE = 'user_song';

    public function insertLike(int $songId, int $userId)
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . static::TABLE . " 
        (user_id, song_id) 
        VALUES (:user_id, :song_id)"
        );
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindValue(':song_id', $songId, \PDO::PARAM_INT);

        $statement->execute();
    }

    public function deleteLike(int $songId, int $userId)
    {
        $statement = $this->pdo->prepare(
            "DELETE FROM " . static::TABLE . " 
        WHERE user_id=:user_id AND song_id=:song_id"
        );
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->bindValue(':song_id', $songId, \PDO::PARAM_INT);

        $statement->execute();
    }
    public function selectVote($userId)
    {
        $statement = $this->pdo->prepare("SELECT song_id FROM " . static::TABLE . " WHERE user_id=:user_id");
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
}
