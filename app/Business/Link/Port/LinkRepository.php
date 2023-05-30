<?php

namespace App\Business\Link\Port;

use App\Business\Link\Domain\Link;

interface LinkRepository {
    public function create(Link $user): Link;

    public function searchBySlug(string $slug): Link|null;
}