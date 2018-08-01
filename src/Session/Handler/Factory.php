<?php

namespace GeekLab\Session\Handler;

use GeekLab\Session;

/**
 * Factory for session storage handlers.
 *
 * Class Factory
 * @package GeekLab\Session\Handler
 */
final class Factory
{
    private $settings;
    private $dataStorage;
    private $dataPHP;

    /**
     * Factory constructor.
     * @param array                      $settings
     * @param Session\Data\DataInterface $dataStorage
     * @param Session\Data\DataInterface $dataPHP
     */
    public function __construct(array $settings, Session\Data\DataInterface $dataStorage, Session\Data\DataInterface $dataPHP)
    {
        $this->settings    = $settings;
        $this->dataStorage = $dataStorage;
        $this->dataPHP     = $dataPHP;
    }

    public function create(): HandlerInterface
    {
        switch ($this->settings['storage'])
        {
            case 'pdo':
                return new PDO($this->settings['db'], $this->dataStorage, $this->dataPHP);
                break;

            case 'file':
                (isset($this->settings['save_path']) && !empty($this->settings['save_path'])) ?: $this->settings['save_path'] = ini_get('session.save_path');
                (isset($this->settings['prefix']) && !empty($this->settings['prefix'])) ?: $this->settings['prefix'] = 'php_session_';

                return new File($this->settings['save_path'], $this->settings['prefix'], $this->dataStorage, $this->dataPHP);
                break;

            default:
                // Throw invalid storage type error
                throw new \Exception('Invalid storage type: "' . $this->settings['storage'] . '" is not supported.');
        }
    }
}