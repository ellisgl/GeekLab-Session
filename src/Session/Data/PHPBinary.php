<?php

namespace GeekLab\Session\Data;

/**
 * Deal with PHPs internal binary session serialized data.
 * Stolen from: https://github.com/wikimedia/php-session-serializer/blob/master/src/Wikimedia/PhpSessionSerializer.php
 *
 * Class PHPBinary
 * @package GeekLab\Session\Data
 */
class PHPBinary implements DataInterface
{
    public function encode(array $sessionData): string
    {
        if (empty($sessionData))
        {
            return '';
        }

        $encodedData = '';

        foreach ($sessionData as $key => $value)
        {
            if (strcmp($key, intval($key)) === 0)
            {
                continue;
            }

            $l = strlen($key);

            if ($l > 127)
            {
                continue;
            }

            $v = serialize($value);

            if ($v === null)
            {
                return null;
            }

            $encodedData .= chr($l) . $key . $v;
        }

        return $encodedData;
    }

    public function decode(string $sessionData): array
    {
        if ('' === $sessionData)
        {
            return [];
        }

        $decodedData = [];

        while ($sessionData !== '' && $sessionData !== false)
        {
            $l = ord($sessionData[0]);

            if (strlen($sessionData) < ($l & 127) + 1)
            {
                return [];
            }

            // "undefined" marker
            if ($l > 127)
            {
                $sessionData = substr($sessionData, ($l & 127) + 1);
                continue;
            }

            $key         = substr($sessionData, 1, $l);
            $sessionData = substr($sessionData, $l + 1);

            if ($sessionData === '' || $sessionData === false)
            {
                return [];
            }

            list($ok, $value) = self::unserializeValue($sessionData);

            if (!$ok)
            {
                return null;
            }

            $decodedData[$key] = $value;
        }

        return $decodedData;
    }

    /**
     * Unserialize a value
     * @param string &$string On success, the portion used is removed
     * @return array ( bool $success, mixed $value )
     */
    /**
     * Unserialize a value
     *
     * @param $string
     * @return array
     */
    private static function unserializeValue(&$string)
    {
        $error = null;

        set_error_handler(function ($errno, $errstr) use (&$error)
        {
            $error = $errstr;
            return true;
        });

        $unserialized = unserialize($string);

        restore_error_handler();

        if ($error !== null)
        {
            return [false, null];
        }

        $serialized = serialize($unserialized);
        $l          = strlen($serialized);

        if (substr($string, 0, $l) !== $serialized)
        {
            return [false, null];
        }

        $string = substr($string, $l);

        return [true, $unserialized];
    }
}