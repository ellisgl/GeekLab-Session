<?php
namespace GeekLab\Session\Handler;

abstract class HandlerAbstract
{
    /**
     * Override the default session handler.
     */
    public function start()
    {
        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );

        // Start the session
        session_start();
    }
}