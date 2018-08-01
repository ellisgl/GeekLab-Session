<?php

namespace GeekLab\Session\Data;

/**
 * Deal with igbinary session serialized data.
 * Stolen from: https://github.com/wikimedia/php-session-serializer/blob/master/src/Wikimedia/PhpSessionSerializer.php
 *
 * Class PHPBinary
 * @package GeekLab\Session\Data
 */
class igbinary implements DataInterface
{
    public function encode(array $sessionData): string
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