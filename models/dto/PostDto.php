<?php

declare(strict_types=1);

namespace app\models\dto;

use app\core\Model;

class PostDto extends Model
{
    private string $title;
    private string $post_text;
    private string $photo;
    private string $genre;
    private int $author_id;
    private string $author_photo;
    private string $author;
    private string $date;
    private string $isLiked;

    /**
     * @param string $title
     * @param string $post_text
     * @param string $photo
     * @param string $genre
     * @param int $author_id
     * @param string $author_photo
     * @param string $author
     * @param String $date
     * @param string $isLiked
     */
    public function __construct(?int $id, string $title, string $post_text, string $photo, string $genre, int $author_id, string $author_photo, string $author, string $date, string $isLiked)
    {
        parent::__construct($id);
        $this->title = $title;
        $this->post_text = $post_text;
        $this->photo = $photo;
        $this->genre = $genre;
        $this->author_id = $author_id;
        $this->author_photo = $author_photo;
        $this->author = $author;
        $this->date = $date;
        $this->isLiked = $isLiked;
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

    public function getGenre(): string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): void
    {
        $this->genre = $genre;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function getAuthorPhoto(): string
    {
        return $this->author_photo;
    }

    public function setAuthorPhoto(string $author_photo): void
    {
        $this->author_photo = $author_photo;
    }

    public function isLiked(): string
    {
        return $this->isLiked;
    }

    public function setIsLiked(string $isLiked): void
    {
        $this->isLiked = $isLiked;
    }

    public function getProperties(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'post_text' => $this->post_text,
            'photo' => $this->photo,
            'genre' => $this->genre,
            'author_id' => $this->author_id,
            'author_photo' => $this->author_photo,
            'author' => $this->author,
            'date' => $this->date,
            'isLiked' => $this->isLiked
        ];
    }
}