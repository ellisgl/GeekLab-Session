<?php

namespace GeekLab\Session\Data;

/**
 * Deal with PHP internal session serialized data.
 * Stolen from: https://github.com/psr7-sessions/session-encode-decode/
 *
 * Class PHPInternal
 * @package GeekLab\Session\Data
 */
class PHP implements DataInterface
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
            $encodedData .= $key . '|' . serialize($value);
        }

        return $encodedData;
    }

    public function decode(string $sessionData): array
    {
        if ('' === $sessionData)
        {
            return [];
        }

        preg_match_all('/(^|;|\})(\w+)\|/i', $sessionData, $matches, PREG_OFFSET_CAPTURE);

        $decodedData = [];
        $lastOffset  = null;
        $currentKey  = '';

        foreach ($matches[2] as $value)
        {
            $offset = $value[1];

            if (null !== $lastOffset)
            {
                $valueText                = substr($sessionData, $lastOffset, $offset - $lastOffset);
                $decodedData[$currentKey] = unserialize($valueText);
            }

            $currentKey = $value[0];
            $lastOffset = $offset + strlen($currentKey) + 1;
        }

        $valueText                = substr($sessionData, $lastOffset);
        $decodedData[$currentKey] = unserialize($valueText);

        return $decodedData;
    }
}