<?php

namespace App\Business\Link\Port\Dto;

readonly class CreateLinkInput {
    public function __construct(
        public string $url,
        public ?string $slug
    ) {}
}