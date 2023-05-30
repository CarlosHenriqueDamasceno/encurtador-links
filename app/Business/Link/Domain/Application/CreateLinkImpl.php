<?php

namespace App\Business\Link\Domain\Application;

use App\Business\Link\Domain\Link;
use App\Business\Link\Port\Application\CreateLink;
use App\Business\Link\Port\Dto\CreateLinkInput;
use App\Business\Link\Port\Dto\LinkOutput;
use App\Business\Link\Port\LinkRepository;
use App\Business\Shared\Exception\BusinessException;

readonly class CreateLinkImpl implements CreateLink {

    public function __construct(private LinkRepository $linkRepository) {}

    public function execute(CreateLinkInput $data): LinkOutput {
        $slugHasBeenGivenByUser = is_null($data->slug);
        $link = $this->validate($data, $slugHasBeenGivenByUser);
        $link = $this->linkRepository->create($link);
        return LinkOutput::fromLink($link);
    }

    private function validateSlug($slug): bool {
        return is_null($this->linkRepository->searchBySlug($slug));
    }

    private function generateNewSlug(string $url): Link {
        return Link::buildNonExistentLink($url, null);
    }

    private function validate(CreateLinkInput $data, bool $slugHasBeenGivenByUser): Link {
        $link = Link::buildNonExistentLink($data->url, $data->slug);
        if ($slugHasBeenGivenByUser) {
            if (!$this->validateSlug($link->slug))
                throw new BusinessException("O slug informado não é válido!");
        } else {
            while (!$this->validateSlug($link->slug)) {
                $link = $this->generateNewSlug($link->url->value);
            }
        }
        return $link;

    }
}