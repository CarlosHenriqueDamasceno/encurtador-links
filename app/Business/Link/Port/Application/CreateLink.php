<?php

namespace App\Business\Link\Port\Application;

use App\Business\Link\Port\Dto\CreateLinkInput;
use App\Business\Link\Port\Dto\LinkOutput;

interface CreateLink {
    public function execute(CreateLinkInput $data): LinkOutput;
}