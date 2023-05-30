<?php

namespace App\Business\Link\Port\Application;

interface GetAllLinks {
    public function execute(int $pageSize, int $page): array;
}