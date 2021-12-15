<?php


namespace Dhi\BlogBundle\Utils;


class Path
{
    public static function get_favicon_path(): ?string
    {
        return self::get_public_path() . $_ENV['FAVICON_PATH'];
    }

    public static function get_public_path(): ?string
    {
        return __DIR__ . '/../../public';
    }

    public static function get_logo_path(): ?string
    {
        try {
            return self::get_public_path() . $_ENV['LOGO_PATH'];
        } catch (\Exception $exception) {
            return null;
        }
    }

    public static function get_logo_light_path(): ?string
    {
        try {
            return self::get_public_path() . $_ENV['LOGO_LIGHT'];
        } catch (\Exception $exception) {
            return null;
        }
    }
}