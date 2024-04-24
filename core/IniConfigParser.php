<?php
declare(strict_types=1);

namespace app\core;

class IniConfigParser
{
    public static function load(): void
    {
        if (!file_exists(PROJECT_DIR . "/config.ini")) {
            return;
        }
        $config = file(PROJECT_DIR . "/config.ini", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($config as $value) {
            $value = trim($value); // remove spaces at the start and end

            if (str_starts_with($value, ";")) {
                continue;
            } // skip comments

            if (str_starts_with($value, "[") && str_ends_with($value, "]")) {
                continue;
            } // skip section declaration

            self::loadLineAsKVP($value);
        }
    }


    private static bool $isSection = false;
    public static function loadSection(string $section): void
    {
        if (!file_exists(PROJECT_DIR . "/config.ini")) {
            return;
        }
        $config = file(PROJECT_DIR . "/config.ini", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($config as $value) {
            $value = trim($value); // remove spaces at the start and end

            if (str_starts_with($value, ";")) {
                continue;
            } // skip comments

            if (str_starts_with($value, "[") && str_ends_with($value, "]") & !(IniConfigParser::$isSection)) {
                if (strcmp(substr($value, 1, strlen($value) - 1) ,$section)) {
                    IniConfigParser::$isSection = true;
                }
            } else if (str_starts_with($value, "[") && str_ends_with($value, "]") & (IniConfigParser::$isSection)) {
                IniConfigParser::$isSection = false;
                break;
            } else if ((IniConfigParser::$isSection)){
                self::loadLineAsKVP($value);
            }
        }
    }

    private static function loadLineAsKVP(string $value):void
    {
        $kvp = explode("=", $value, 2); // separate line to key, value
        $kvp[0] = rtrim($kvp[0]);
        $kvp[1] = ltrim($kvp[1]);

        $_ENV[$kvp[0]] = $kvp[1];
        $_SERVER[$kvp[0]] = $kvp[1];

        putenv($kvp[0]."=".$kvp[1]);
    }
}