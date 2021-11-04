<?php

namespace App\Model;

class SongManager extends AbstractManager
{
    public const TABLE = 'song';
    /**
     * Get all songs from database by posted_at
     *
     */
    public function showSongsByDate(string $date): array
    {
        $query = ("SELECT *, user.pseudo FROM " . self::TABLE . " JOIN user ON user.id = song.user_id WHERE posted_at =:date ORDER BY rating DESC");
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('date', $date, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Insert new song in database
     */
    public function insert(array $song): int
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (`url`) VALUES (:url)");
        $statement->bindValue('url', $song['url'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();

    public function selectAllByUserId($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM song s WHERE s.user_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();

    }
}
