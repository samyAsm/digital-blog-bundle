<?php


namespace DhiBlogBundle\Utils;


trait EnvUtils
{
    protected static function getEnv(string $key, $default = null): ?string
    {
        return $_ENV[$key] ?? $default;
    }

    protected static function getEnvInt(string $key, int $default = 0): int
    {
        return intval(self::getEnv($key, $default));
    }

    protected static function getEnvFloat(string $key, float $default = 0): int
    {
        return floatval(self::getEnv($key, $default));
    }

    protected static function getEnvBool(string $key, bool $default = false): bool
    {
        $res = self::getEnv($key, $default);
        return boolval($res) && $res !== 'false';
    }

    protected static function isProdEnv(): bool
    {
        return preg_match('/^prod/i', self::getEnv('APP_ENV', 'prod'));
    }

    protected static function isTestEnv(): bool
    {
        return preg_match('/^test$/i', self::getEnv('APP_ENV', 'prod'));
    }

    protected static function isDevEnv(): bool
    {
        return preg_match('/^dev/i', self::getEnv('APP_ENV', 'prod'));
    }

    protected static function isDebugMode(): bool
    {
        return self::getEnvBool('APP_DEBUG', false);
    }
}
