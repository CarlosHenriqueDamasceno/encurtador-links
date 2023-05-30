<?php

namespace App\Business\Link\Port\Application;

use App\Business\Link\Port\Dto\LinkOutput;
use App\Business\Link\Port\Dto\UpdateLinkInput;

interface UpdateLink {
    public function execute(int $id, UpdateLinkInput $data): LinkOutput;
}