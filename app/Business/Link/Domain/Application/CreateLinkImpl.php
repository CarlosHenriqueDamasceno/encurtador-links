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
        $slugHasBeenGivenByUser = !is_null($data->slug);
        $link = Link::buildNonExistentLink($data->url, $data->slug);
        if ($slugHasBeenGivenByUser) {
            if (!$this->checkSlugAvailability($link->slug))
                throw new BusinessException("O slug informado já está em uso!");
        } else {
            while (!$this->checkSlugAvailability($link->slug)) {
                $link = $this->generateNewSlug($link->url->value);
            }
        }
        $link = $this->linkRepository->create($link);
        return LinkOutput::fromLink($link);
    }

    private function checkSlugAvailability($slug): bool {
        return is_null($this->linkRepository->searchBySlug($slug));
    }

    private function generateNewSlug(string $url): Link {
        return Link::buildNonExistentLink($url, null);
    }

}