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
     * Override PHPs default session handler.
     *
     * @param array $settings
     * @throws \Exception
     */
    public static function start(array $settings): void
    {
        // Setup data PHP type
        $dataPHP = ArrayTranslation::create(ini_get('session.serialize_handler'));

        // Setup data storage type
        (isset($settings['data']) && !empty($settings['data'])) ?: $settings['data'] = ini_get('session.serialize_handler');
        $dataStorage = ArrayTranslation::create($settings['data']);

        // Make sure settings['storage] is set.
        (isset($settings['storage']) && !empty($settings['storage'])) ?: $settings['storage'] = 'file';

        // Create the session handler object
        $handler = Session\Handler\Factory::create($settings, $dataStorage, $dataPHP);

        // Start 'er up!
        $handler->start();
    }
}