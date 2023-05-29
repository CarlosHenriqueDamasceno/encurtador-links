<?php

namespace App\Business\Link\Domain;

use App\Business\Link\Domain\ValueObject\Url;

readonly class Link {
    public ?int $id;
    public Url $url;
    public string $slug;

    public function __construct(?int $id, string $url, string $slug) {
        $this->id = $id;
        $this->url = Url::build($url);
        $this->slug = $slug;
    }

    public static function buildNonExistentLinkWithSlug(
        string $url,
        ?string $slug
    ): Link {
        if (is_null($slug)) {
            $slug = self::generateRandomSlug();
        }
        return new Link(null, $url, $slug);
    }

    private static function generateRandomSlug(): string {
        $dictionary = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $slug = '';
        $length = rand(6, 8);

        for ($i = 0; $i < $length; $i++) {
            $slug .= $dictionary[rand(0, strlen($dictionary) - 1)];
        }

        return $slug;
    }

}