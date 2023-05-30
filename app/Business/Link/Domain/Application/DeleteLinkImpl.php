<?php

namespace App\Business\Link\Domain\Application;

use App\Business\Link\Port\Application\DeleteLink;
use App\Business\Link\Port\LinkRepository;
use App\Business\Shared\Exception\BusinessException;
use App\Business\Shared\Exception\ResourceNotFoundException;

readonly class DeleteLinkImpl implements DeleteLink {

    public function __construct(private LinkRepository $linkRepository) {}

    public function execute(int $id): void {
        try {
            $this->linkRepository->find($id);
            $this->linkRepository->delete($id);
        } catch (ResourceNotFoundException $exception) {
            throw new BusinessException("Link não encontrado com o id $id!");
        } catch (\Exception $exception) {
            throw new BusinessException("Serviço indisponível!");
        }
    }
}