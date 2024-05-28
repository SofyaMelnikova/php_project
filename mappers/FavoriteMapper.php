<?php

namespace app\mappers;

use app\core\Application;
use PDO;
use PDOStatement;

class FavoriteMapper
{
    private PDOStatement $saveFavorite;
    private PDOStatement $deleteFavorite;
    private PDOStatement $selectByUserId;
    private PDOStatement $findByIds;
    private PDOStatement $deleteAllFavoriteByPostId;

    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Application::$app->getDatabase()->getPdo();

        $this->saveFavorite = $this->pdo->prepare("insert into favorite (user_id, post_id) VALUES (:userId, :postId);");

        $this->deleteFavorite = $this->pdo->prepare("delete from favorite where user_id = :userId and post_id = :postId;");

        $this->deleteAllFavoriteByPostId = $this->pdo->prepare("delete from favorite where post_id = :postId;");

        $this->selectByUserId = $this->pdo->prepare("select post_id from favorite where user_id = :userId;");

        $this->findByIds = $this->pdo->prepare("select * from favorite where user_id = :userId and post_id = :postId;");
    }

    public function saveFavorite(int $userId, int $postId): void
    {
        $this->saveFavorite->execute([":userId" => $userId, ":postId" => $postId]);
    }

    public function deleteFavorite(int $userId, int $postId): void
    {
        $this->deleteFavorite->execute([":userId" => $userId, ":postId" => $postId]);
    }

    public function deleteFavoriteByPostId(int $postId): void
    {
        $this->deleteAllFavoriteByPostId->execute([":postId" => $postId]);
    }

    public function selectByUserId(int $userId): array
    {
        $this->selectByUserId->execute([":userId" => $userId]);
        return $this->selectByUserId->fetch(\PDO::FETCH_NAMED);
    }

    public function findByIds(int $userId, int $postId): bool
    {
        $this->findByIds->execute([":userId" => $userId, ":postId" => $postId]);
        $res = ($this->findByIds->fetch(\PDO::FETCH_NAMED));
        if ($res != null) {
            return true;
        }
        return false;
    }
}