<?php

declare(strict_types=1);

namespace app\migrations;

use app\core\Migration;

class Migration_4 extends Migration
{

    function getVersion(): int
    {
        return 4;
    }

    function up(): void
    {
        $this->database->getPdo()->query("create table usr_post
(
    user_id bigint,
    post_id bigint,

    foreign key (user_id) references usr (id),
    foreign key (post_id) references post (id)
);");

        parent::up();
    }

    function down(): void
    {
        $this->database->getPdo()->query("drop table usr_post;");
    }
}