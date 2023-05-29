<?php

namespace Tests\Unit\User;

use App\Business\Shared\Exception\BusinessException;
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

    public function test_should_not_create_user_invalid_email(): void {
        $encryptService = \Mockery::mock(EncryptService::class);
        $encryptService
            ->shouldReceive('encrypt')
            ->with(UserUnitTestUtils::$uncryptedPassword)
            ->andReturn(
                UserUnitTestUtils::$encryptedPassword
            );
        $userRepository = \Mockery::mock(UserRepository::class);
        $userRepository
            ->shouldReceive('searchByEmail')
            ->with(UserUnitTestUtils::$updatedEmail)
            ->andReturn(
                UserUnitTestUtils::$secondaryExistentUser
            );
        $input = new CreateUserInput(
            UserUnitTestUtils::$userName, UserUnitTestUtils::$updatedEmail,
            UserUnitTestUtils::$uncryptedPassword
        );
        $createUser = new CreateUserImpl($userRepository, $encryptService);
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage(UserUnitTestUtils::$alreadyTakenEmailErrorMessage);
        $createUser->execute($input);
    }
}