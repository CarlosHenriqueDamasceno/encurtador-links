<?php

namespace Tests\Unit\User;

use App\Business\User\Domain\User;
use App\Business\User\Port\EncryptService;

class UserUnitTestUtils {
    public static string $userName = "Carlos Henrique";
    public static string $updatedUserName = "Carlos Henrique editado";
    public static string $validEmail = "carlos@teste.com";
    public static string $invalidEmail = "carlos@teste";
    public static string $updatedEmail = "carloseditado@teste.com";
    public static string $uncryptedPassword = "123123123";
    public static string $invalidPassword = "123";
    public static string $encryptedPassword = "88ea39439e74fa27c09a4fc0bc8ebe6d00978392";

    public static string $alreadyTakenEmailErrorMessage = "O email fornecido já está em uso por outro usuário!";
    public static string $userNotFoundErrorMessage = "Usuário não encontrado com o id 1!";
    public static string $invalidEmailErrorMessage = "O E-mail fornecido não é válido!";
    public static string $invalidPasswordErrorMessage = "A senha deve conter pelo menos 8 caracteres!";
    public static string $invalidIdErrorMessage = "O id informado não é válido!";

    public static User $existentUser;
    public static User $secondaryExistentUser;
    public static User $toSaveUser;
    public static User $updatedUser;

    public static function init(): void {
        $encryptService = \Mockery::mock(EncryptService::class);
        $encryptService
            ->shouldReceive('encrypt')
            ->with(self::$uncryptedPassword)
            ->andReturn(
                self::$encryptedPassword
            );
        self::$toSaveUser = User::buildNonExistentUser(
            self::$userName,
            self::$validEmail,
            self::$uncryptedPassword,
            $encryptService
        );
        self::$existentUser = User::buildExistentUser(
            1,
            self::$userName,
            self::$validEmail,
            self::$encryptedPassword
        );
        self::$secondaryExistentUser = User::buildExistentUser(
            2,
            self::$userName,
            self::$updatedEmail,
            self::$encryptedPassword
        );
        self::$updatedUser = User::buildExistentUser(
            1,
            self::$updatedUserName,
            self::$updatedEmail,
            self::$encryptedPassword
        );
    }

}

UserUnitTestUtils::init();