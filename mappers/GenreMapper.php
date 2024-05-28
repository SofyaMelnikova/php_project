<?php

declare(strict_types=1);

namespace app\mappers;

use app\core\Mapper;
use app\core\Model;
use app\models\Genre;
use PDOStatement;

class GenreMapper extends Mapper
{
    private PDOStatement $select;
    private PDOStatement $selectAll;
    private PDOStatement $insert;
    private PDOStatement $update;
    private PDOStatement $delete;

    public function __construct()
    {
        parent::__construct();
        $this->select = $this->pdo->prepare("SELECT * FROM genre WHERE id = :id");

        $this->selectAll = $this->pdo->prepare("SELECT * FROM genre");

        $this->insert = $this->pdo->prepare(
            "INSERT INTO genre(name) 
                VALUES (:name)"
        );

        $this->delete = $this->pdo->prepare("DELETE FROM genre WHERE id = :id");

        $this->update = $this->pdo->prepare(
            "UPDATE genre
            SET name = :name
            WHERE id = :id"
        );
    }

    protected function doInsert(Model $model): Model
    {
        $this->insert->execute([
            ":name" => $model->getName()
        ]);
        $model->setId((int)$this->pdo->lastInsertId());
        return $model;
    }

    protected function doUpdate(Model $model): void
    {
        $this->update->execute([
            ":name" => $model->getName()
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
        return new Genre(
            array_key_exists("id", $data)? $data["id"]: null,
            $data["name"]
        );
    }
}