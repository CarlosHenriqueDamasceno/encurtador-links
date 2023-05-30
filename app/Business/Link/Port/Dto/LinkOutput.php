<?php

namespace App\Business\Link\Port\Dto;

use App\Business\Link\Domain\Link;

readonly class LinkOutput {
    public function __construct(
        public int $id,
        public string $url,
        public string $slug
    ) {}

    public static function fromLink(Link $link): LinkOutput {
        return new LinkOutput(
            $link->id,
            $link->url->value,
            $link->slug
        );
    }
}