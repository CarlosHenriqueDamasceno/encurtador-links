<?php

namespace App\Business\User\Port;

interface EncryptService {
    public function encrypt(string $string): string;
}