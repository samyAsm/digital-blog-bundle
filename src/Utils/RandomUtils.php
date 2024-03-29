<?php


namespace Dhi\BlogBundle\Utils;


trait RandomUtils
{
    use EnvUtils;

    protected function getRandomString(string $alphabet, int $length): string
    {
        $string = '';
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $length; $i++)
            $string .= $alphabet[rand(0, $alphaLength)];

        return $string;
    }

    protected function getRandomUrlToken(int $length = 64): string
    {
        return $this->getRandomString('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', $length);
    }

    protected function getRandomCode(int $length = 4): string
    {
        return $this->getRandomString('0123456789', $length);
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function timestamp()
    {
        return DateUtil::getDate()->format('Y-m-d-H-i');
    }

    /**
     *
     * Generate v4 UUID
     *
     * Version 4 UUIDs are pseudo-random.
     */
    protected function getUuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
}
