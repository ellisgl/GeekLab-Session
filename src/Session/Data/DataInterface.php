<?php

namespace GeekLab\Session\Data;

interface DataInterface
{
    public function encode(array $sessionData): string;

    public function decode(string $sessionData): array;
}