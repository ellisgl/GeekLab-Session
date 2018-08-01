<?php

namespace GeekLab\Session\Data;

/**
 * Deal with PHPs serialize()'d session data.
 *
 * Class PHPSerialize
 * @package GeekLab\Session\Data
 */
class PHPSerialize implements DataInterface
{
    public function encode(array $sessionData): string
    {
        if (empty($sessionData))
        {
            return '';
        }

        return serialize($sessionData);
    }

    public function decode(string $sessionData): array
    {
        if('' === $sessionData)
        {
            return [];
        }

        return unserialize($sessionData);
    }
}