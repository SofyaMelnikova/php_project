<?php

declare(strict_types=1);

namespace app\migrations;

use app\core\Migration;

class Migration_6 extends Migration
{

    function getVersion(): int
    {
        return 6;
    }

    function up(): void
    {
        $this->database->getPdo()->query("create table favorite
(
    user_id bigint,
    post_id bigint,

    foreign key (user_id) references usr (id),
    foreign key (post_id) references post (id)
)");

        parent::up();
    }

    function down(): void
    {
        $this->database->getPdo()->query("drop table favorite;");
    }
}