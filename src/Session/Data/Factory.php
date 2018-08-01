<?php

namespace GeekLab\Session\Data;

/**
 * Factory for session data format handlers.
 *
 * Class Factory
 * @package GeekLab\Session\Data
 */
final class Factory
{
    private $dataType;

    public function __construct($dataType)
    {
        $this->dataType = $dataType;
    }

    /**
     * @param $dataType
     * @return DataInterface
     * @throws \Exception
     */
    public function create(): DataInterface
    {
        switch ($this->dataType)
        {
            case 'php':
                return new PHP();
                break;

            case 'php_serialize':
                return new PHPSerialize();
                break;

            case 'php_binary':
                return new PHPBinary();
                break;

            case 'wddx':
                return new WDDX();
                break;

            case 'igbinary':
                return new igbinary();
                break;

            case 'json':
                return new JSON();
                break;

            case 'xml':
                return new XML();
                break;

            default:
                // Throw invalid 'data' error
                throw new \Exception('Invalid data type: "' . $this->dataType . '" is not supported.');
        }
    }
}