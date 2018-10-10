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

        // Start the session engine.
        session_start();

        // Perform garbage collection (PHP sometimes is bad at this)
        if (isset($_SESSION['__lastAccess']) && $_SESSION['__lastAccess'] < (time() - ini_get('session.gc_maxlifetime')))
        {
            // Restart session and perform garbage collection.
            session_unset();
            session_destroy();
            session_regenerate_id();
            session_gc();
        }

        // Update last access time for garbage collection.
        $_SESSION['__lastAccess'] = time();
    }

    public function gc($ttl)
    {
        // Calculate what is to be deemed old
        $old = time() - ($ttl);

        call_user_func(array($this, '_gc'), $old);
    }
}