<?php

namespace App\Http\Controllers;

use App\Business\User\Port\Application\CreateUser;
use App\Business\User\Port\Dto\CreateUserInput;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Http\JsonResponse;

class CreateUserController extends Controller {

    public function __construct(private CreateUser $createUser) {}

    public function __invoke(CreateUserRequest $request): JsonResponse {
        $fields = $request->validated();
        $input = new CreateUserInput($fields['name'], $fields['email'], $fields['password']);
        $result = $this->createUser->execute($input);
        return response()->json($result);
    }
}
