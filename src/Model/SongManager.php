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
        $query = ("SELECT song.*, user.pseudo, user.github_name
                    FROM " . self::TABLE . "
                    JOIN user ON user.id = song.user_id
                    WHERE posted_at =:date
                    ORDER BY rating DESC");
        $statement = $this->pdo->prepare($query);
        $statement->bindValue('date', $date, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }

    /**
     * Insert new song in database
     */
    public function insert(array $song, $userId): int
    {
        $statement = $this->pdo->prepare('
        INSERT INTO ' . self::TABLE . '(title, url, posted_at, user_id, rating)
        VALUES (:title, :url, NOW(), :user_id, 0)
        ');
        $statement->bindValue(':title', $song['title'], \PDO::PARAM_STR);
        $statement->bindValue(':url', $song['url'], \PDO::PARAM_STR);
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function selectAllByUserId($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM song s WHERE s.user_id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countSongsOfByDay(string $date)
    {
        $statement = $this->pdo->prepare("SELECT COUNT(*) as count FROM song WHERE posted_at= DATE ( NOW() )");
        $statement->bindValue(':date', $date, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchColumn();
    }

    public function likeFor(int $songId): bool
    {
        $statement = $this->pdo->prepare("UPDATE song SET rating = rating+1 WHERE id =:id");
        $statement->bindValue('id', $songId, \PDO::PARAM_INT);

        return $statement->execute();
    }

    public function dislikeFor(int $songId): bool
    {
        $statement = $this->pdo->prepare("UPDATE song SET rating = rating-1 WHERE id =:id");
        $statement->bindValue('id', $songId, \PDO::PARAM_INT);

        return $statement->execute();
    }
}
