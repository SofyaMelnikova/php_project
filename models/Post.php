<?php

declare(strict_types=1);

namespace app\models;

use app\core\Model;

class Post extends Model
{
    private string $title;
    private string $post_text;
    private string $photo;
    private int $genre_id;
    private int $author_id;
    private String $date;

    /**
     * @param string $title
     * @param string $post_text
     * @param string $photo
     * @param int $genre_id
     * @param int $author_id
     * @param String $date
     */
    public function __construct(?int $id, string $title, string $post_text, string $photo, int $genre_id, int $author_id, string $date)
    {
        parent::__construct($id);
        $this->title = $title;
        $this->post_text = $post_text;
        $this->photo = $photo;
        $this->genre_id = $genre_id;
        $this->author_id = $author_id;
        $this->date = $date;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPostText(): string
    {
        return $this->post_text;
    }

    public function setPostText(string $post_text): void
    {
        $this->post_text = $post_text;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }

    public function getGenreId(): int
    {
        return $this->genre_id;
    }

    public function setGenreId(int $genre_id): void
    {
        $this->genre_id = $genre_id;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }
}