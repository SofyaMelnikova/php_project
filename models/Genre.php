<?php

namespace app\models;

use app\core\Model;

class Genre extends Model
{
    private string $name;

    /**
     * @param string $name
     */
    public function __construct(?int $id, string $name)
    {
        parent::__construct($id);
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}