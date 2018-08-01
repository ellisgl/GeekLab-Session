<?php

namespace GeekLab\Session\Handler;

use GeekLab\Session\Data\DataInterface;

/**
 * File storage session handler
 * Stolen from: https://github.com/Dynom/SessionHandler/blob/master/D/SessionDriver/File.php
 *
 * Class File
 * @package GeekLab\Session\Handler
 */
class File extends HandlerAbstract implements HandlerInterface
{
    private $save_path;
    private $prefix;
    private $dataPHP;
    private $dataStorage;
    private $fh = null;

    /**
     * File handler constructor.
     *
     * @param string        $save_path
     * @param string        $prefix
     * @param DataInterface $dataStorage
     * @param DataInterface $dataPHP
     * @throws \Exception
     */
    public function __construct(string $save_path, string $prefix, DataInterface $dataStorage, DataInterface $dataPHP)
    {
        $this->save_path   = $this->normalizePath($save_path); // I know I shouldn't do this here...
        $this->prefix      = $prefix;
        $this->dataStorage = $dataStorage;
        $this->dataPHP     = $dataPHP;
    }

    /**
     * Open session handler.
     *
     * @return bool
     */
    public function open(): bool
    {
        return (is_writable($this->save_path)) ? TRUE : FALSE;
    }

    /**
     * Close session handler.
     *
     * @return bool
     */
    public function close(): bool
    {
        return (is_resource($this->fh)) ? fclose($this->fh) : FALSE;
    }

    /**
     * Read a session.
     *
     * @param string $id
     * @return string
     */
    public function read(string $id): string
    {
        $file        = $this->save_path . $this->prefix . $id;
        $sessionData = '';

        if (is_file($file))
        {
            $this->fh    = fopen($file, 'r');
            $sessionData = (filesize($file) > 0) ? $this->dataPHP->encode($this->dataStorage->decode(fread($this->fh, filesize($file)))) : '';
        }

        return $sessionData;
    }

    /**
     * Write a session.
     *
     * @param  string $id
     * @param  string $sessionData
     * @return bool
     */
    public function write(string $id, string $sessionData): bool
    {
        $file        = $this->save_path . $this->prefix . $id;
        $this->fh    = fopen($file, 'w');
        $sessionData = $this->dataStorage->encode($this->dataPHP->decode($sessionData)); // De/Encode session data.

        fwrite($this->fh, $sessionData);

        return fclose($this->fh);
    }

    /**
     * Destroy a session.
     *
     * @param  string $id
     * @return bool
     */
    public function destroy(string $id): bool
    {
        $file = $this->save_path . $this->prefix . $id;

        return unlink($file);
    }

    /**
     * Garbage collection.
     *
     * @param  int $ttl - Time To Live in seconds
     * @return bool
     */
    public function gc(int $ttl): bool
    {
        // Calculate what is to be deemed old
        $old = time() - ($ttl);

        // Create a file search
        $expr = $this->save_path . $this->prefix . '*';

        foreach (glob($expr) as $file)
        {
            if (filetime($file) < $old)
            {
                unlink($file);
            }
        }

        return TRUE;
    }

    /**
     * Normalize the path, making sure it exists, readable and ends with a
     * separator.
     *
     * @param string $path
     * @return string
     * @throws \Exception - Invalid path
     */
    private function normalizePath(string $path): string
    {
        $realPath = realpath($path);

        if ($realPath === false)
        {
            throw new \Exception('Invalid path: "' . $path . '" does not exists.');
        }

        return $realPath . DIRECTORY_SEPARATOR;
    }
}