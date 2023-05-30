<?php

namespace Tests\Unit\Link;

use App\Business\Link\Domain\Application\CreateLinkImpl;
use App\Business\Link\Domain\Link;
use App\Business\Link\Port\Dto\CreateLinkInput;
use App\Business\Link\Port\LinkRepository;
use App\Business\Shared\Exception\BusinessException;
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

    public function test_should_not_create_an_link_with_already_taken_slug(): void {
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
                LinkUnitTestUtils::$existentLink
            );
        $input = new CreateLinkInput(
            LinkUnitTestUtils::$url,
            LinkUnitTestUtils::$slug
        );
        $createLink = new CreateLinkImpl($linkRepository);
        $this->expectException(BusinessException::class);
        $this->expectExceptionMessage(LinkUnitTestUtils::$alreadyTakenSlugErrorMessage);
        $createLink->execute($input);
    }

    public function test_should_create_an_link_with_no_given_slug(): void {
        $linkRepository = \Mockery::mock(LinkRepository::class);
        $linkRepository->shouldReceive('create')->with(
            \Mockery::on(
                function ($arg) {
                    return $arg instanceof Link && strlen($arg->slug) >= 6 && strlen(
                            $arg->slug
                        ) <= 8;
                }
            )
        )->andReturn(
            LinkUnitTestUtils::$existentLink
        );
        $linkRepository
            ->shouldReceive('searchBySlug')
            ->with(
                \Mockery::on(
                    function ($param) {
                        $valid = false;
                        $paramLength = strlen($param);
                        if (is_string($param) && $paramLength >= 6 && $paramLength <= 8) {
                            $valid = true;
                        }
                        return $valid;
                    }
                )
            )
            ->andReturn(
                null
            );
        $input = new CreateLinkInput(
            LinkUnitTestUtils::$url,
            null
        );
        $createLink = new CreateLinkImpl($linkRepository);
        $newLink = $createLink->execute($input);
        $this->assertEquals(1, $newLink->id);
    }

    public function test_should_create_an_link_with_no_given_slug_trying_two_times(): void {
        $linkRepository = \Mockery::mock(LinkRepository::class);
        $linkRepository->shouldReceive('create')->with(
            \Mockery::on(
                function ($arg) {
                    return $arg instanceof Link && strlen($arg->slug) >= 6 && strlen(
                            $arg->slug
                        ) <= 8;
                }
            )
        )->andReturn(
            LinkUnitTestUtils::$existentLink
        );
        $times = 1;
        $linkRepository
            ->shouldReceive('searchBySlug')
            ->with(
                \Mockery::on(
                    function ($param) use (&$times) {
                        if ($times < 2) {
                            $times++;
                            return false;
                        }
                        $valid = false;
                        $paramLength = strlen($param);
                        if (is_string($param) && $paramLength >= 6 && $paramLength <= 8) {
                            $valid = true;
                        }
                        return $valid;
                    }
                )
            )
            ->andReturn(
                null
            );
        $input = new CreateLinkInput(
            LinkUnitTestUtils::$url,
            null
        );
        $createLink = new CreateLinkImpl($linkRepository);
        $newLink = $createLink->execute($input);
        $this->assertEquals(1, $newLink->id);
    }
}