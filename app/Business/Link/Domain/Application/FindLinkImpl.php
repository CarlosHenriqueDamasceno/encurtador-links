<?php

namespace App\Business\Link\Domain\Application;

use App\Business\Link\Port\Application\FindLink;
use App\Business\Link\Port\Dto\LinkOutput;
use App\Business\Link\Port\LinkRepository;
use App\Business\Shared\Exception\BusinessException;
use App\Business\Shared\Exception\ResourceNotFoundException;

readonly class FindLinkImpl implements FindLink {

    public function __construct(private LinkRepository $linkRepository) {}

    public function execute(int $id): LinkOutput {
        try {
            $link = $this->linkRepository->find($id);
            return LinkOutput::fromLink($link);
        } catch (ResourceNotFoundException $exception) {
            throw new BusinessException("Link não encontrado com o id $id!");
        } catch (\Exception $exception) {
            throw new BusinessException("Serviço indisponível!");
        }
    }
}