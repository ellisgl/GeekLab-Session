<?php
namespace GeekLab\Session\Handler;

interface HandlerInterface
{
    public function start();                                // Created in the HandlerAbstract
    public function open();
    public function close();
    public function read(string $id);
    public function write(string $id, string $sessionData);
    public function destroy(string $id);
    public function gc(int $ttl);
    public function _gc(int $old);                          // Created in the HandlerAbstract
}