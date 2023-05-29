<?php

namespace App\Business\User\Domain\ValueObject;

use App\Business\Shared\Exception\BusinessException;
use App\Business\User\Port\EncryptService;

readonly class Password {
    public function __construct(public string $value) {}

    public static function build(string $value, EncryptService $encryptService): Password {
        if (strlen($value) > 8) {
            return new Password($encryptService->encrypt($value));
        } else {
            throw new BusinessException("A senha deve conter pelo menos 8 caracteres!");
        }
    }
}