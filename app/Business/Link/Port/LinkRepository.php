<?php

namespace App\Business\Link\Port;

use App\Business\Link\Domain\Link;

interface LinkRepository {
    public function create(Link $user): Link;

    public function getAll(int $pageSize, int $page);

    public function find(int $id): Link;

    public function searchBySlug(string $slug): Link|null;

    public function update(Link $link): Link;

    public function delete(int $id): void;
}