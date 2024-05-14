<?php

declare(strict_types=1);

namespace app\migrations;

use app\core\Migration;

class Migration_0 extends Migration
{

    function getVersion(): int
    {
        return 0;
    }

    function up(): void
    {
        $this->database->getPdo()->query("create table usr (
            id serial primary key,
            username varchar(40),
            email varchar(50),
            password varchar,
            photo varchar,
            description text
        );");
        parent::up();
    }

    function down(): void
    {
        $this->database->getPdo()->query("DROP TABLE usr");
    }
}