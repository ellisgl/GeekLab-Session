<?php

namespace GeekLab;


/**
 * GeekLab\Session is a robust PHP session handler.
 *
 * Class Session
 * @package GeekLab
 */
class Session
{
    /**
     * @var array
     */
    private $settings;

    /**
     * Session constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Create the session object.
     *
     * @throws \Exception - Invalid serial type.
     */
    public function start()
    {
        // Setup data PHP type
        $dataPHP = ArrayTranslation::create(ini_get('session.serialize_handler'));

        // Setup data storage type
        (isset($this->settings['data']) && !empty($this->settings['data'])) ?: $this->settings['data'] = ini_get('session.serialize_handler');
        $dataStorage = ArrayTranslation::create($this->settings['data']);

        // Make sure settings['storage] is set.
        (isset($this->settings['storage']) && !empty($this->settings['storage'])) ?: $this->settings['storage'] = 'file';

        // Create the session handler object
        $handler = Session\Handler\Factory::create($this->settings, $dataStorage, $dataPHP);

        // Start 'er up!
        $handler->start();
    }
}