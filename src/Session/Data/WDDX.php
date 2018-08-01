<?php

namespace GeekLab\Session\Data;

/**
 * Deal with WDDX session serialized data.
 * Stolen from: https://github.com/wikimedia/php-session-serializer/blob/master/src/Wikimedia/PhpSessionSerializer.php
 *
 * Class PHPBinary
 * @package GeekLab\Session\Data
 */
class WDDX implements DataInterface
{
    public function encode($sessionData): string
    {
        if (empty($sessionData))
        {
            return '';
        }

        return '';
    }

    public function decode(string $sessionData): array
    {
        if('' === $sessionData)
        {
            return [];
        }

        return [];
    }
}