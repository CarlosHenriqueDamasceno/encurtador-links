<?php

namespace App\Business\Link\Domain;

use App\Business\Link\Domain\ValueObject\Url;

readonly class Link {
    public ?int $id;
    public Url $url;
    public string $slug;

    private function __construct(?int $id, string $url, string $slug) {
        $this->id = $id;
        $this->url = Url::build($url);
        $this->slug = $slug;
    }

    public static function buildNonExistentLink(
        string $url,
        ?string $slug
    ): Link {
        if (is_null($slug)) {
            $slug = self::generateRandomSlug();
        }
        return new Link(null, $url, $slug);
    }

    public static function buildExistentLink(
        int $id,
        string $url,
        string $slug
    ): Link {
        return new Link($id, $url, $slug);
    }

    public function copyWith(?string $url): Link {
        return new Link(
            id: $this->id,
            url: (!is_null($url) ? $url : $this->url),
            slug: $this->slug
        );
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