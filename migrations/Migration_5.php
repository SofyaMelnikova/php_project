<?php

declare(strict_types=1);

namespace app\migrations;

use app\core\Migration;

class Migration_5 extends Migration
{

    function getVersion(): int
    {
        return 5;
    }

    function up(): void
    {
        $this->database->getPdo()->query("create table post_comment
(
    post_id    bigint,
    comment_id bigint,

    foreign key (post_id) references post (id),
    foreign key (comment_id) references comment (id)
);");

        parent::up();
    }

    function down(): void
    {
        $this->database->getPdo()->query("drop table post_comment;");
    }
}