<?php

namespace App\Business\Link\Domain\ValueObject;

use App\Business\Shared\Exception\BusinessException;

readonly class Url {
    private function __construct(public string $value) {}

    public static function build(string $value): Url {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return new Url($value);
        } else {
            throw new BusinessException("A URL fornecida não é válida!");
        }
    }
}