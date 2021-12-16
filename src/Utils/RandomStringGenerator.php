<?php


namespace Dhi\BlogBundle\Utils;

/**
 * Simple class to generate a random strong string value
 *
 * Solution taken from here:
 * https://stackoverflow.com/a/13733588/1056679
 * Class RandomStringGenerator
 * @package App\Utils
 */
class RandomStringGenerator
{
    /** @var string */
    protected static $alphabet;

    /** @var int */
    protected static $alphabetLength;

    /**
     * @param string $alphabet
     */
    protected static function initialize($alphabet = '')
    {
        if ('' !== $alphabet) {
            self::setAlphabet($alphabet);
        } else {
            self::setAlphabet(
                implode(range('a', 'z'))
                . implode(range('A', 'Z'))
                . implode(range(0, 9))
            );
        }
    }

    /**
     * @param string $alphabet
     */
    public static function setAlphabet($alphabet)
    {
        self::$alphabet = $alphabet;
        self::$alphabetLength = strlen($alphabet);
    }

    /**
     * @param int $length
     * @param bool $timestamp
     * @return string
     */
    public static function generate($length, $timestamp = false)
    {
        self::initialize();

        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $randomKey = self::getRandomInteger(0, self::$alphabetLength);
            $token .= strtolower(self::$alphabet[$randomKey]);
        }

        if ($timestamp)
            return $token.time();

        return $token;
    }

    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    protected static function getRandomInteger($min, $max)
    {
        $range = ($max - $min);

        if ($range < 0) {
            // Not so random...
            return $min;
        }

        $log = log($range, 2);

        // Length in bytes.
        $bytes = (int) ($log / 8) + 1;

        // Length in bits.
        $bits = (int) $log + 1;

        // Set all lower bits to 1.
        $filter = (int) (1 << $bits) - 1;

        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));

            // Discard irrelevant bits.
            $rnd = $rnd & $filter;

        } while ($rnd >= $range);

        return ($min + $rnd);
    }
}