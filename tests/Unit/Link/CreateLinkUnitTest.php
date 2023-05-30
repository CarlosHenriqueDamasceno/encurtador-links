<?php

namespace Tests\Unit\Link;

use App\Business\Link\Domain\Application\CreateLinkImpl;
use App\Business\Link\Port\Dto\CreateLinkInput;
use App\Business\Link\Port\LinkRepository;
use PHPUnit\Framework\TestCase;

class CreateLinkUnitTest extends TestCase {
    public function test_should_create_an_link_with_given_slug(): void {
        $linkRepository = \Mockery::mock(LinkRepository::class);
        $linkRepository->shouldReceive('create')->with(
            \Mockery::on(
                function ($arg) {
                    return LinkUnitTestUtils::$toSaveLink == $arg;
                }
            )
        )->andReturn(
            LinkUnitTestUtils::$existentLink
        );
        $linkRepository
            ->shouldReceive('searchBySlug')
            ->with(LinkUnitTestUtils::$slug)
            ->andReturn(
                null
            );
        $input = new CreateLinkInput(
            LinkUnitTestUtils::$url,
            LinkUnitTestUtils::$slug
        );
        $createLink = new CreateLinkImpl($linkRepository);
        $newLink = $createLink->execute($input);
        $this->assertEquals(1, $newLink->id);
        $this->assertEquals(LinkUnitTestUtils::$slug, $newLink->slug);
    }
}