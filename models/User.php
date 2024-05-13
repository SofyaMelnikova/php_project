<?php

declare(strict_types=1);

namespace app\models;

use app\core\Model;

class User extends Model
{
    private string $username;
    private string $email;
    private string $password;
    private string $photo;
    private string $description;

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param string $photo
     * @param string $description
     */
    public function __construct(?int $id, string $username, string $email, string $password, string $photo, string $description)
    {
        parent::__construct($id);
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->photo = $photo;
        $this->description = $description;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): void
    {
        $this->photo = $photo;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}