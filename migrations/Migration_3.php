<?php

declare(strict_types=1);

namespace app\migrations;

use app\core\Migration;

class Migration_3 extends Migration
{

    function getVersion(): int
    {
        return 3;
    }

    function up(): void
    {
        $this->database->getPdo()->query("create table comment(
    id serial primary key,
    author_id bigint,
    comment_text text,
    time timestamptz,
    post_id bigint,

    foreign key (author_id) references usr(id),
    foreign key (post_id) references post(id)
)");

        parent::up();
    }

    function down(): void
    {
        $this->database->getPdo()->query("drop table comment;");
    }
}