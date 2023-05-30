<?php

namespace App\Business\User\Domain;

use App\Business\Shared\Exception\BusinessException;
use App\Business\User\Domain\ValueObject\Email;
use App\Business\User\Domain\ValueObject\Password;
use App\Business\User\Port\EncryptService;

readonly class User {

    public ?int $id;
    public string $name;
    public Email $email;
    public Password $password;

    private function __construct(
        ?int $id, string $name, string $email, Password $password
    ) {

        $email = Email::build($email);

        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public static function buildNonExistentUser(
        string $name,
        string $email,
        string $password,
        EncryptService $encryptService
    ): User {
        $password = Password::build($password, $encryptService);
        return new User(null, $name, $email, $password);
    }

    public static function buildExistentUser(
        int $id,
        string $name,
        string $email,
        string $password
    ): User {
        if ($id < 1)
            throw new BusinessException("O id informado não é válido!");
        $password = new Password($password);
        return new User($id, $name, $email, $password);
    }

    public function copyWith(?int $id = null, ?string $name = null, ?string $email = null): User {
        return new User(
            id: (!is_null($id) ? $id : $this->id),
            name: (!is_null($name) ? $name : $this->name),
            email: (!is_null($email) ? $email : $this->email),
            password: $this->password
        );
    }
}