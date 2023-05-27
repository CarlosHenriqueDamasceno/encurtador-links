<?php

namespace Tests\Unit\User;

use App\Business\Shared\Exception\BusinessException;
use App\Business\User\Domain\User;
use App\Business\User\Port\EncryptService;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase {

    public function test_should_instantiate_a_new_user(): void {
        $encryptService = \Mockery::mock(EncryptService::class);
        $encryptService->shouldReceive('encrypt')->with(UserUnitTestUtils::$uncryptedPassword)
            ->andReturn(
                UserUnitTestUtils::$encryptedPassword
            );
        $user = User::buildNonExistentUser(
            UserUnitTestUtils::$userName, UserUnitTestUtils::$validEmail,
            UserUnitTestUtils::$uncryptedPassword,
            $encryptService
        );
        $this->assertEquals(UserUnitTestUtils::$encryptedPassword, $user->password->value);
        $this->assertNull($user->id);
    }

    public function test_should_instantiate_a_existent_user(): void {
        $user = User::buildExistentUser(
            1, UserUnitTestUtils::$userName, UserUnitTestUtils::$validEmail,
            UserUnitTestUtils::$encryptedPassword
        );
        $this->assertEquals(1, $user->id);
    }

    public function test_should_not_instantiate_user_with_invalid_email(): void {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage(UserUnitTestUtils::$invalidEmailErrorMessage);
        User::buildExistentUser(
            1, UserUnitTestUtils::$userName, UserUnitTestUtils::$invalidEmail,
            UserUnitTestUtils::$encryptedPassword
        );
    }

    public function test_should_not_instantiate_user_with_invalid_password(): void {
        $encryptService = \Mockery::mock(EncryptService::class);
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage(UserUnitTestUtils::$invalidPasswordErrorMessage);
        User::buildNonExistentUser(
            UserUnitTestUtils::$userName, UserUnitTestUtils::$validEmail,
            UserUnitTestUtils::$invalidPassword,
            $encryptService
        );
    }

    public function test_should_not_instantiate_existent_user_with_invalid_id(): void {
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage(UserUnitTestUtils::$invalidIdErrorMessage);
        User::buildExistentUser(
            0, UserUnitTestUtils::$userName, UserUnitTestUtils::$validEmail,
            UserUnitTestUtils::$invalidPassword
        );
    }
}