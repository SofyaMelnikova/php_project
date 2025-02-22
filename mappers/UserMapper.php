<?php

declare(strict_types=1);

namespace app\mappers;

use app\core\Mapper;
use app\core\Model;
use app\models\User;
use PDOStatement;

class UserMapper extends Mapper
{
    private PDOStatement $select;
    private PDOStatement $selectAll;
    private PDOStatement $insert;
    private PDOStatement $update;
    private PDOStatement $delete;
    private PDOStatement $selectByEmail;


    public function __construct()
    {
        parent::__construct();
        $this->select = $this->pdo->prepare("SELECT * FROM usr WHERE id = :id");

        $this->selectAll = $this->pdo->prepare("SELECT * FROM usr");

        $this->insert = $this->pdo->prepare(
            "INSERT INTO usr(username, email, password, photo, description) 
                VALUES (:username, :email, :password, :photo, :description)"
        );

        $this->delete = $this->pdo->prepare("DELETE FROM usr WHERE id = :id");

        $this->update = $this->pdo->prepare(
            "UPDATE usr
            SET username = :username,
                email = :email,
                password = :password,
                photo = :photo,
                description = :description
            WHERE id = :id"
        );

        $this->selectByEmail = $this->pdo->prepare("SELECT * FROM usr WHERE email = :email");
    }

    protected function doInsert(Model $model): Model
    {
        $this->insert->execute([
            ":username" => $model->getUsername(),
            ":email" => $model->getEmail(),
            ":password" => $model->getPassword(),
            ":photo" => $model->getPhoto(),
            ":description" => $model->getDescription()
        ]);
        $model->setId((int)$this->pdo->lastInsertId());
        return $model;
    }

    protected function doUpdate(Model $model): void
    {
        $this->update->execute([
            ":id" => $model->getId(),
            ":username" => $model->getUsername(),
            ":email" => $model->getEmail(),
            ":password" => $model->getPassword(),
            ":photo" => $model->getPhoto(),
            ":description" => $model->getDescription()
        ]);
    }

    protected function doDelete(int $id): void
    {
        $this->delete->execute([":id"=>$id]);
    }

    protected function doSelect(int $id): array
    {
        $this->select->execute([":id"=>$id]);
        return $this->select->fetch(\PDO::FETCH_NAMED);
    }

    protected function doSelectByEmail(string $email): array|bool
    {
        $this->selectByEmail->execute([":email"=>$email]);
        return $this->selectByEmail->fetch(\PDO::FETCH_NAMED);
    }

    public function SelectByEmail(string $email): ?Model
    {

        $res = $this->doSelectByEmail($email);
        if (!$res) return null;
        return $this->createObject($this->doSelectByEmail($email));
    }

    protected function doSelectAll(): array
    {
        $this->selectAll->execute();
        return $this->selectAll->fetchAll(\PDO::FETCH_NAMED);
    }

    public function getInstance(): Mapper
    {
        return $this;
    }

    public function createObject(array $data): Model
    {
       return new User(
           array_key_exists("id", $data)? $data["id"]: null,
           $data["username"],
           $data["email"],
           $data["password"],
           $data["photo"],
           $data["description"]
       );
    }
}