<?php

namespace Tests\Unit\User;

use PHPUnit\Framework\TestCase;

class CreateUserTest extends TestCase {
    public function test_should_create_an_user(): void {
        $encrypterService = \Mockery::mock(EncryptService::class);
        $encrypterService
            ->shouldReceive('encrypt')
            ->with(UserUnitTestUtils::$uncryptedPassword)
            ->andReturn(
                UserUnitTestUtils::$encryptedPassword
            );
        $userRepository = \Mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('create')->with(
            \Mockery::on(
                function ($arg) {
                    return UserUnitTestUtils::$toSaveUser == $arg;
                }
            )
        )->andReturn(
            UserUnitTestUtils::$existentUser
        );
        $createUser = new CreateUserImpl($userRepository, $encrypterService);
        $input = new CreateUserInput(
            UserUnitTestUtils::$userName, UserUnitTestUtils::$validEmail,
            UserUnitTestUtils::$uncryptedPassword
        );
        $newUser = $createUser->execute($input);
        $this->assertEquals(1, $newUser->id);
    }
}