<?php

namespace App\Business\Link\Port\Dto;

readonly class UpdateLinkInput {
    public function __construct(
        public ?string $url
    ) {}
}