<?php

namespace app\mappers;

use app\core\Mapper;
use app\core\Model;
use app\models\Comment;
use PDOStatement;

class CommentMapper extends Mapper
{
    private PDOStatement $select;
    private PDOStatement $selectAll;
    private PDOStatement $insert;
    private PDOStatement $update;
    private PDOStatement $delete;

    public function __construct()
    {
        parent::__construct();
        $this->select = $this->pdo->prepare("SELECT * FROM comment WHERE id = :id");

        $this->selectAll = $this->pdo->prepare("SELECT * FROM comment");

        $this->insert = $this->pdo->prepare(
            "INSERT INTO comment(author_id, comment_text, time, post_id) 
                VALUES (:author_id, :comment_text, :time, :post_id)"
        );

        $this->delete = $this->pdo->prepare("DELETE FROM comment WHERE id = :id");

        $this->update = $this->pdo->prepare(
            "UPDATE post
            SET author_id = :author_id,
                comment_text = :comment_text,
                time = :time,
                post_id = :post_id
            WHERE id = :id"
        );
    }

    protected function doInsert(Model $model): Model
    {
        $this->insert->execute([
            ":author_id" => $model->getAuthorId(),
            ":comment_text" => $model->getCommentText(),
            ":time" => $model->getTime(),
            ":post_id" => $model->getPostId()
        ]);
        $model->setId((int)$this->pdo->lastInsertId());
        return $model;
    }

    protected function doUpdate(Model $model): void
    {
        $this->update->execute([
            ":author_id" => $model->getAuthorId(),
            ":comment_text" => $model->getCommentText(),
            ":time" => $model->getTime(),
            ":post_id" => $model->getPostId()
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
        return new Comment(
            array_key_exists("id", $data) ? $data["id"] : null,
            (int)$data["author_id"],
            $data["comment_text"],
            $data["time"],
            (int)$data["post_id"]
        );
    }
}