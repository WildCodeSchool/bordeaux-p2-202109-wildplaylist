<?php

namespace App\Model;

class UserManager extends AbstractManager
{
    public const TABLE = 'user';
    public function selectUserById($id)
    {
        $statement = $this->pdo->prepare("SELECT * FROM user  WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch();
    }

    public function create(array $userData)
    {
        $statement = $this->pdo->prepare('
        INSERT INTO user (pseudo, mail, password, github_name, is_admin) 
        VALUES (:pseudo, :mail, :password, :github_name, false)
        ');
        $statement->bindValue(':pseudo', $userData['pseudo'], \PDO::PARAM_STR);
        $statement->bindValue(':mail', $userData['mail'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $userData['password'], \PDO::PARAM_STR);
        $statement->bindValue(':github_name', $userData['github-name'], \PDO::PARAM_STR);
        $statement->execute();
        return $this->pdo->lastInsertId();
    }

    public function selectOneByEmail(string $mail)
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . static::TABLE . " WHERE mail=:mail");
        $statement->bindValue(':mail', $mail, \PDO::PARAM_STR);
        $statement->execute();

        return $statement->fetch();
    }
}
