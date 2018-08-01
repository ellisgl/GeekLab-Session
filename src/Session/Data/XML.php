<?php

namespace GeekLab\Session\Data;

use Spatie\ArrayToXml\ArrayToXml;
use Vyuldashev\XmlToArray\XmlToArray;

/**
 * Convert session data to/from XML
 * Class XML
 * @package GeekLab\Session\Data
 */
class XML implements DataInterface
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

        $encodedData = ArrayToXml::convert($sessionData, 'session');

        return $encodedData;
    }

    /**
     * @param string $sessionData
     * @return array
     */
    public function decode(string $sessionData): array
    {
        if ('' === $sessionData)
        {
            return [];
        }

        $decodedData = XmlToArray::convert($sessionData);

        return $decodedData['session'];
    }
}