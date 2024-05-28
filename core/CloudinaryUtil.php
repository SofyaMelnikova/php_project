<?php

namespace app\core;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;

class CloudinaryUtil
{
    public static function upload(string $fileUrl): ?string
    {
        IniConfigParser::load();

        $config = Configuration::instance([
            'cloud' => [
                'cloud_name' => $_ENV["cloud_name"],
                'api_key' => $_ENV["api_key"],
                'api_secret' => $_ENV["api_secret"],
            'url' => [
                'secure' => true]]]);

        try {
            return (new Cloudinary($config))->uploadApi()->upload($fileUrl)['url'];
        } catch (ApiError $e) {
            echo 'Failed to download photo';
        }
        return null;

    }
}