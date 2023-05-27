<?php

namespace App\Business\User\Domain\Application;

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
        //TODO: verificar se o email já está em uso, e criar o teste para isso
        $user = User::buildNonExistentUser(
            $input->name, $input->email, $input->password, $this->encryptService
        );
        return UserOutput::fromUser($this->repo->create($user));
    }
}