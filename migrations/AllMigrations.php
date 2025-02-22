<?php

declare(strict_types=1);

namespace app\migrations;

function getMigrations(): array {
    return [new Migration_0(),
        new Migration_1(),
        new Migration_2(),
        new Migration_3(),
        new Migration_4(),
        new Migration_5(),
        new Migration_6(),
        new Migration_7()];
}
