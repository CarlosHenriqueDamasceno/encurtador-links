<?php

namespace Tests\Unit\Link;

class CreateLinkUnitTest {
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