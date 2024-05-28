<?php

declare(strict_types=1);

namespace app\mappers;

use app\core\Collection;
use app\core\Mapper;
use app\core\Model;
use app\models\Post;
use PDOStatement;

class PostMapper extends Mapper
{
    private PDOStatement $select;
    private PDOStatement $selectAll;
    private PDOStatement $insert;
    private PDOStatement $update;
    private PDOStatement $delete;
    private PDOStatement $selectByUserId;
    private PDOStatement $selectByTitle;
    private PDOStatement $selectByGenreId;

    public function __construct()
    {
        parent::__construct();
        $this->select = $this->pdo->prepare("SELECT * FROM post WHERE id = :id");

        $this->selectAll = $this->pdo->prepare("SELECT * FROM post ORDER BY date DESC");

        $this->insert = $this->pdo->prepare(
            "INSERT INTO post(title, post_text, photo, genre_id, author_id, date) 
                VALUES (:title, :post_text, :photo, :genre_id, :author_id, :date)"
        );

        $this->delete = $this->pdo->prepare("DELETE FROM post WHERE id = :id");

        $this->update = $this->pdo->prepare(
            "UPDATE post
            SET title = :title,
                post_text = :post_text,
                photo = :photo,
                genre_id = :genre_id,
                author_id = :author_id,
                date = :date
            WHERE id = :id"
        );

        $this->selectByUserId = $this->pdo->prepare("select * from post where author_id = :userId ORDER BY date DESC");

        $this->selectByTitle = $this->pdo->prepare("select * from post where title ilike :title ORDER BY date DESC");

        $this->selectByGenreId = $this->pdo->prepare("select * from post where genre_id = :genreId ORDER BY date DESC");
    }

    protected function doInsert(Model $model): Model
    {
        $this->insert->execute([
            ":title" => $model->getTitle(),
            ":post_text" => $model->getPostText(),
            ":photo" => $model->getPhoto(),
            ":genre_id" => $model->getGenreId(),
            ":author_id" => $model->getAuthorId(),
            ":date" => $model->getDate()
        ]);
        $model->setId((int)$this->pdo->lastInsertId());
        return $model;
    }

    protected function doUpdate(Model $model): void
    {
        $this->update->execute([
            ":title" => $model->getTitle(),
            ":post_text" => $model->getPostText(),
            ":photo" => $model->getPhoto(),
            ":genre_id" => $model->getGenreId(),
            ":author_id" => $model->getAuthorId(),
            ":date" => $model->getDate()
        ]);
    }

    protected function doDelete(int $id): void
    {
        $this->delete->execute([":id" => $id]);
    }

    protected function doSelect(int $id): array
    {
        $this->select->execute([":id" => $id]);
        return $this->select->fetch(\PDO::FETCH_NAMED);
    }

    protected function doSelectByUserId(int $userId): array
    {
        $this->selectByUserId->execute([":userId" => $userId]);
        return $this->selectByUserId->fetchAll(\PDO::FETCH_NAMED);
    }

    public function SelectByUserId(int $userId): Collection {
        return new Collection($this->doSelectByUserId($userId), $this->getInstance());
    }

    protected function doSelectByTitle(string $title): array
    {
        $this->selectByTitle->execute([":title" => '%'.$title.'%']);
        return $this->selectByTitle->fetchAll(\PDO::FETCH_NAMED);
    }

    public function SelectByTitle(string $title): Collection {
        return new Collection($this->doSelectByTitle($title), $this->getInstance());
    }

    protected function doSelectByGenreId(int $genreId): array
    {
        $this->selectByGenreId->execute([":genreId" => $genreId]);
        return $this->selectByGenreId->fetchAll(\PDO::FETCH_NAMED);
    }

    public function SelectByGenreId(int $genreId): Collection {
        return new Collection($this->doSelectByGenreId($genreId), $this->getInstance());
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
        return new Post(
            array_key_exists("id", $data) ? $data["id"] : null,
            $data["title"],
            $data["post_text"],
            $data["photo"],
            (int)$data["genre_id"],
            (int)$data["author_id"],
            $data["date"]
        );
    }
}