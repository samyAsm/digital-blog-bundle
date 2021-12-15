<?php


namespace DhiBlogBundle\Utils;

trait RegexUtils
{
    protected function isEmpty($var): bool
    {
        return empty($var) && $var !== '0';
    }

    protected function isValidEmail(?string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    protected function isInternationalPhoneNumber(?string $phone_number): bool
    {
        if ($phone_number)
            return preg_match(
                '/^(\+|00)?[0-9]{6,15}$/',
                preg_replace('/[^+0-9]/', '', $phone_number) // Ignore non numeric
            );

        return false;
    }

    protected function isValidPhoneNumber(?string $phone_number, $country_code = "237"): bool
    {
        if ($phone_number)
            return preg_match(
                '/^((\+|00)?' . $country_code . ')?6[5789][0-9]{7}$/',
                preg_replace('/[^0-9]/', '', $phone_number) // Ignore non numeric
            );

        return false;
    }

    protected function parsePhoneNumber(?string $phone_number, $country_code = "237"): ?string
    {
        if (!self::isValidPhoneNumber($phone_number, $country_code))
            return null;

        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        $phone_number = preg_replace('/^00/', '', $phone_number);
        if (!preg_match("/^$country_code/", $phone_number))
            $phone_number = $country_code . $phone_number;

        return $phone_number;
    }

    protected function minifyPhoneNumber(?string $phone_number, $country_code = "237"): ?string
    {
        $phone_number = $this->parsePhoneNumber($phone_number);

        if ($phone_number)
            return preg_replace("/^$country_code/", '', $phone_number);

        return null;
    }

    protected function formatMoney($amount): string
    {
        $amount = doubleval($amount);

        if ($amount == 0)
            return 0;

        if ($amount < 0)
            return '-' . $this->formatMoney(-$amount);

        $parts = explode('.', $amount . '');
        $intPart = strrev(chunk_split(strrev(ltrim($parts[0], '0')), 3, ' '));

        if (count($parts) > 1 && intval($parts[1]) != 0)
            $intPart .= ',' . rtrim($parts[1], '0');

        return trim($intPart);
    }

    protected function truncate(?string $str, int $maxLength = 255): ?string
    {
        if (!$str || strlen($str) <= $maxLength)
            return $str;

        return substr($str, 0, $maxLength - 3) . '...';
    }

    protected function isValidPercent(?float $value): bool
    {
        return $value !== null && $value >= 0 && $value <= 100;
    }
}
