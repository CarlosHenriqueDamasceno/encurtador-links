<?php

namespace App\Business\User\Domain\Application;

use App\Business\Shared\Exception\BusinessException;
use App\Business\Shared\Exception\ResourceNotFoundException;
use App\Business\User\Port\Application\UpdateUser;
use App\Business\User\Port\Dto\UpdateUserInput;
use App\Business\User\Port\Dto\UserOutput;
use App\Business\User\Port\UserRepository;

readonly class UpdateUserImpl implements UpdateUser {

    public function __construct(private UserRepository $repo) {}

    public function execute(int $id, UpdateUserInput $data): UserOutput {
        try {
            $this->validate($id, $data->email);
            $user = $this->repo->find($id);
            $user = $user->copyWith(name: $data->name, email: $data->email);
            return UserOutput::fromUser($this->repo->update($user));
        } catch (ResourceNotFoundException $exception) {
            throw new BusinessException("Usuário não encontrado com o id $id!");
        } catch (\Exception $exception) {
            if ($exception instanceof BusinessException)
                throw $exception;
            throw new BusinessException("Serviço indisponível!");
        }
    }

    private function validate($id, $email): void {
        $userWithSameEmail = $this->repo->searchByEmail($email);
        if ($userWithSameEmail && $userWithSameEmail->id != $id)
            throw new BusinessException("O email fornecido já está em uso por outro usuário!");
    }
}