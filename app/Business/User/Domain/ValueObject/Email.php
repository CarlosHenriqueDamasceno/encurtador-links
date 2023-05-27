<?php

namespace App\Business\User\Domain\ValueObject;

use App\Business\Shared\Exception\BusinessException;

readonly class Email {
    private function __construct(public string $value) {}

    public static function build(string $value): Email {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return new Email($value);
        } else {
            throw new BusinessException("O E-mail fornecido não é válido!");
        }
    }
}