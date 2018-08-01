<?php

namespace GeekLab\Session\Data;

/**
 * Convert session data to/from JSON
 *
 * Class JSON
 * @package GeekLab\Session\Data
 */
class JSON implements DataInterface
{
    /**
     * @param array $sessionData
     * @return string
     */
    public function encode(array $sessionData): string
    {
        if (empty($sessionData))
        {
            return '';
        }

        return json_encode($sessionData);
    }

    /**
     * @param string $sessionData
     * @return array
     */
    public function decode(string $sessionData): array
    {
        if('' === $sessionData)
        {
            return [];
        }

        return json_decode($sessionData, TRUE);
    }
}