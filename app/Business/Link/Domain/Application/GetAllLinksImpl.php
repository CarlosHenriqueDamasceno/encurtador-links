<?php

namespace App\Business\Link\Domain\Application;

use App\Business\Link\Port\Application\GetAllLinks;
use App\Business\Link\Port\LinkRepository;

readonly class GetAllLinksImpl implements GetAllLinks {

    public function __construct(private LinkRepository $linkRepository) {}

    public function execute(int $pageSize, int $page): array {
        return $this->linkRepository->getAll($pageSize, $page);
    }
}