<?php

namespace App\Providers;

use App\Business\User\Domain\Application\CreateUserImpl;
use App\Business\User\Port\Application\CreateUser;
use App\Business\User\Port\EncryptService;
use App\Business\User\Port\UserRepository;
use App\Repositories\EloquentUserRepository;
use App\Services\EncryptServiceImpl;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->app->bind(EncryptService::class, EncryptServiceImpl::class);
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(CreateUser::class, CreateUserImpl::class);
    }
}