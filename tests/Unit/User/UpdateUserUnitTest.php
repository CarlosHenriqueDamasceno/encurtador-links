<?php

namespace Tests\Unit\User;

use App\Business\Shared\Exception\BusinessException;
use App\Business\Shared\Exception\ResourceNotFoundException;
use App\Business\User\Domain\Application\UpdateUserImpl;
use App\Business\User\Port\Dto\UpdateUserInput;
use App\Business\User\Port\UserRepository;
use PHPUnit\Framework\TestCase;

class UpdateUserUnitTest extends TestCase {
    public function test_should_update_user(): void {
        $userRepository = \Mockery::mock(UserRepository::class);
        $userRepository
            ->shouldReceive('find')
            ->with(1)
            ->andReturn(
                UserUnitTestUtils::$existentUser
            );
        $userRepository
            ->shouldReceive('searchByEmail')
            ->with(UserUnitTestUtils::$updatedEmail)
            ->andReturn(
                null
            );
        $userRepository
            ->shouldReceive('update')
            ->with(
                \Mockery::on(
                    function ($param) { return $param == UserUnitTestUtils::$updatedUser; }
                )
            )
            ->andReturn(
                UserUnitTestUtils::$updatedUser
            );
        $input = new UpdateUserInput(
            UserUnitTestUtils::$updatedUserName, UserUnitTestUtils::$updatedEmail
        );
        $updateUser = new UpdateUserImpl($userRepository);
        $user = $updateUser->execute(1, $input);
        $this->assertEquals(UserUnitTestUtils::$updatedUserName, $user->name);
        $this->assertEquals(UserUnitTestUtils::$updatedEmail, $user->email);
    }

    public function test_should_not_update_user_invalid_id(): void {
        $userRepository = \Mockery::mock(UserRepository::class);
        $userRepository
            ->shouldReceive('find')
            ->with(1)
            ->andThrow(
                ResourceNotFoundException::class
            );
        $userRepository
            ->shouldReceive('searchByEmail')
            ->with(UserUnitTestUtils::$updatedEmail)
            ->andReturn(
                null
            );
        $input = new UpdateUserInput(
            UserUnitTestUtils::$updatedUserName, UserUnitTestUtils::$updatedEmail
        );
        $update = new UpdateUserImpl($userRepository);
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage(UserUnitTestUtils::$userNotFoundErrorMessage);
        $update->execute(1, $input);
    }

    public function test_should_not_update_user_invalid_email(): void {
        $userRepository = \Mockery::mock(UserRepository::class);
        $userRepository
            ->shouldReceive('find')
            ->with(1)
            ->andThrow(
                ResourceNotFoundException::class
            );
        $userRepository
            ->shouldReceive('searchByEmail')
            ->with(UserUnitTestUtils::$updatedEmail)
            ->andReturn(
                UserUnitTestUtils::$secondaryExistentUser
            );
        $input = new UpdateUserInput(
            UserUnitTestUtils::$updatedUserName, UserUnitTestUtils::$updatedEmail
        );
        $update = new UpdateUserImpl($userRepository);
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage(UserUnitTestUtils::$alreadyTakenEmailErrorMessage);
        $update->execute(1, $input);
    }
}