<?php

namespace Tests\Unit\Link;

use App\Business\Link\Domain\Link;

class LinkUnitTestUtils {
    public static string $url = "https://www.google.com/";
    public static string $slug = "42cZthl";

    public static Link $toSaveLink;
    public static Link $existentLink;

    public static function init(): void {
        self::$toSaveLink = Link::buildNonExistentLink(self::$url, self::$slug);
        self::$existentLink = Link::buildExistentLink(1, self::$url, self::$slug);
    }
}

LinkUnitTestUtils::init();