<?php

declare(strict_types=1);

namespace app\models;

use app\core\Model;

class Comment extends Model
{
    private int $author_id;
    private string $comment_text;
    private string $time;
    private int $post_id;

    /**
     * @param int $author_id
     * @param string $comment_text
     * @param string $time
     * @param int $post_id
     */
    public function __construct(?int $id, int $author_id, string $comment_text, string $time, int $post_id)
    {
        parent::__construct($id);
        $this->author_id = $author_id;
        $this->comment_text = $comment_text;
        $this->time = $time;
        $this->post_id = $post_id;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function getCommentText(): string
    {
        return $this->comment_text;
    }

    public function setCommentText(string $comment_text): void
    {
        $this->comment_text = $comment_text;
    }

    public function getTime(): string
    {
        return $this->time;
    }

    public function setTime(string $time): void
    {
        $this->time = $time;
    }

    public function getPostId(): int
    {
        return $this->post_id;
    }

    public function setPostId(int $post_id): void
    {
        $this->post_id = $post_id;
    }
}