<?php

namespace App\Business\Link\Port\Application;

use App\Business\Link\Port\Dto\LinkOutput;

interface FindLink {
    public function execute(int $id): LinkOutput;
}