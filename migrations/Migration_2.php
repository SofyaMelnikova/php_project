<?php

declare(strict_types=1);

namespace app\migrations;

use app\core\Migration;

class Migration_2 extends Migration
{

    function getVersion(): int
    {
        return 2;
    }

    function up(): void
    {
        $this->database->getPdo()->query("create table post (
    id serial primary key,
    title varchar,
    post_text text,
    photo varchar,
    genre_id bigint,
    author_id bigint,
    date timestamptz,

    foreign key (genre_id) references genre(id),
    foreign key (author_id) references usr(id)
);");

        parent::up();
    }

    function down(): void
    {
        $this->database->getPdo()->query("drop table post;");
    }
}