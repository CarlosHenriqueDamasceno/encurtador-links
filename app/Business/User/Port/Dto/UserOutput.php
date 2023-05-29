<?php

namespace App\Business\User\Port\Dto;

use App\Business\User\Domain\User;

readonly class UserOutput {

    public function __construct(
        public int $id,
        public string $name,
        public string $email,
    ) {}

    public static function fromUser(User $user): UserOutput {
        return new UserOutput(
            $user->id,
            $user->name,
            $user->email->value
        );
    }
}