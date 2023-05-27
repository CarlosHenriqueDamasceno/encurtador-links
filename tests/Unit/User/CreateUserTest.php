<?php

namespace Tests\Unit\User;

use App\Business\User\Domain\Application\CreateUserImpl;
use App\Business\User\Port\Dto\CreateUserInput;
use App\Business\User\Port\EncryptService;
use App\Business\User\Port\UserRepository;
use PHPUnit\Framework\TestCase;

class CreateUserTest extends TestCase {
    public function test_should_create_an_user(): void {
        $encryptService = \Mockery::mock(EncryptService::class);
        $encryptService
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
        $createUser = new CreateUserImpl($userRepository, $encryptService);
        $input = new CreateUserInput(
            UserUnitTestUtils::$userName, UserUnitTestUtils::$validEmail,
            UserUnitTestUtils::$uncryptedPassword
        );
        $newUser = $createUser->execute($input);
        $this->assertEquals(1, $newUser->id);
    }
}