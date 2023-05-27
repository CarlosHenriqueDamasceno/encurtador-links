<?php

namespace App\Business\User\Domain\Application;

use App\Business\Shared\Exception\BusinessException;
use App\Business\User\Domain\User;
use App\Business\User\Port\Application\CreateUser;
use App\Business\User\Port\Dto\CreateUserInput;
use App\Business\User\Port\Dto\UserOutput;
use App\Business\User\Port\EncryptService;
use App\Business\User\Port\UserRepository;

readonly class CreateUserImpl implements CreateUser {

    public function __construct(
        private UserRepository $repo, private EncryptService $encryptService
    ) {}

    public function execute(CreateUserInput $input): UserOutput {
        $this->validate($input->email);
        $user = User::buildNonExistentUser(
            $input->name, $input->email, $input->password, $this->encryptService
        );
        return UserOutput::fromUser($this->repo->create($user));
    }

    private function validate($email): void {
        $userWithSameEmail = $this->repo->searchByEmail($email);
        if ($userWithSameEmail)
            throw new BusinessException("O email fornecido já está em uso por outro usuário!");
    }
}