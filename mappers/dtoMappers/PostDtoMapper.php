<?php

namespace app\mappers\dtoMappers;

use app\core\Mapper;
use app\core\Model;
use app\models\dto\PostDto;

class PostDtoMapper extends Mapper
{

    protected function doInsert(Model $model): Model
    {
        return $model;
    }

    protected function doUpdate(Model $model): void
    {
        // TODO: Implement doUpdate() method.
    }

    protected function doDelete(int $id): void
    {
        // TODO: Implement doDelete() method.
    }

    protected function doSelect(int $id): array
    {
        return [];
    }

    protected function doSelectAll(): array
    {
        return [];
    }

    public function getInstance(): Mapper
    {
        return $this;
    }

    public function createObject(array $data): Model
    {
        return new PostDto(
            array_key_exists("id", $data) ? $data["id"] : null,
            $data["title"],
            $data["post_text"],
            $data["photo"],
            $data["genre"],
            (int)$data["author_id"],
            $data["author_photo"],
            $data["author"],
            $data["date"],
            $data["isLiked"]
        );
    }
}