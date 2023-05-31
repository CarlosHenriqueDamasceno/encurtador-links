<?php

namespace App\Services;

use App\Business\User\Port\EncryptService;
use Illuminate\Support\Facades\Hash;

class EncryptServiceImpl implements EncryptService {

    public function encrypt(string $string): string {
        return Hash::make($string);
    }
}