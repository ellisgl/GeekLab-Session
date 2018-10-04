<?php

namespace GeekLab\Session\Handler;

use GeekLab\ArrayTranslation;
use GeekLab\Session;

/**
 * Factory for session storage handlers.
 *
 * Class Factory
 * @package GeekLab\Session\Handler
 */
final class Factory
{
    /**
     * Factory builder
     *
     * @param array                                 $settings
     * @param ArrayTranslation\TranslationInterface $dataStorage
     * @param ArrayTranslation\TranslationInterface $dataPHP
     * @return HandlerInterface
     * @throws \Exception
     */
    public static function create(array $settings, ArrayTranslation\TranslationInterface $dataStorage, ArrayTranslation\TranslationInterface $dataPHP): HandlerInterface
    {
        switch ($settings['storage'])
        {
            case 'pdo':
                return new PDO($settings['db'], $dataStorage, $dataPHP);
                break;

            case 'file':
                (isset($settings['save_path']) && !empty($settings['save_path'])) ?: $settings['save_path'] = ini_get('session.save_path');
                (isset($settings['prefix']) && !empty($settings['prefix'])) ?: $settings['prefix'] = 'php_session_';

                return new File($settings['save_path'], $settings['prefix'], $dataStorage, $dataPHP);
                break;

            default:
                // Throw invalid storage type error
                throw new \Exception('Invalid storage type: "' . $settings['storage'] . '" is not supported.');
        }
    }
}