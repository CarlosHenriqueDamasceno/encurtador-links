<?php

namespace App\Business\Link\Port\Application;

interface DeleteLink {
    public function execute(int $id): void;
}