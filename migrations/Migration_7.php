<?php

declare(strict_types=1);

namespace app\migrations;

use app\core\Migration;

class Migration_7 extends Migration
{

    function getVersion(): int
    {
        return 7;
    }

    function up(): void
    {
        $this->database->getPdo()->query("insert into genre (name) values ('action'),
                             ('animation'),
                             ('biographical'),
                             ('cartoon'),
                             ('comedy'),
                             ('detective'),
                             ('documentary'),
                             ('family'),
                             ('fantasy'),
                             ('horror'),
                             ('melodrama'),
                             ('musical'),
                             ('romance'),
                             ('sci-fi'),
                             ('thriller');");

        parent::up();
    }

    function down(): void
    {
        $this->database->getPdo()->query("truncate table genre;");
    }
}