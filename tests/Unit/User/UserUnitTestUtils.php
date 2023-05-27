<?php

namespace Tests\Unit\User;

class UserUnitTestUtils {
    public static string $userName = "Carlos Henrique";
    public static string $updatedUserName = "Carlos Henrique editado";
    public static string $validEmail = "carlos@teste.com";
    public static string $invalidEmail = "carlos@teste";
    public static string $uncryptedPassword = "123123123";
    public static string $invalidPassword = "123";
    public static string $encryptedPassword = "88ea39439e74fa27c09a4fc0bc8ebe6d00978392";

    public static string $userNotFoundErrorMessage = "Usuário não encontrado com o id 1!";
    public static string $invalidEmailErrorMessage = "O E-mail informado não é válido!";
    public static string $invalidPasswordErrorMessage = "A senha deve conter pelo menos 8 caracteres!";
    public static string $invalidIdErrorMessage = "O id informado não é válido!";

    public static User $existentUser;
    public static User $toSaveUser;
    public static User $updatedUser;

    public static function init(): void {
        $encryptService = \Mockery::mock(EncryptService::class);
        $encryptService
            ->shouldReceive('encrypt')
            ->with(UserUnitTestUtils::$uncryptedPassword)
            ->andReturn(
                UserUnitTestUtils::$encryptedPassword
            );
        self::$toSaveUser = User::buildNonExistentUser(
            UserUnitTestUtils::$userName, UserUnitTestUtils::$validEmail,
            UserUnitTestUtils::$uncryptedPassword,
            $encryptService
        );
        self::$existentUser = User::buildExistentUser(
            1,
            UserUnitTestUtils::$userName,
            UserUnitTestUtils::$validEmail,
            UserUnitTestUtils::$encryptedPassword
        );
        self::$updatedUser = User::buildExistentUser(
            1,
            UserUnitTestUtils::$updatedUserName,
            UserUnitTestUtils::$validEmail,
            UserUnitTestUtils::$encryptedPassword
        );
    }

}

UserUnitTestUtils::init();