<?php

namespace App\Business\Link\Domain\Application;

use App\Business\Link\Port\Application\UpdateLink;
use App\Business\Link\Port\Dto\LinkOutput;
use App\Business\Link\Port\Dto\UpdateLinkInput;
use App\Business\Link\Port\LinkRepository;
use App\Business\Shared\Exception\BusinessException;
use App\Business\Shared\Exception\ResourceNotFoundException;

readonly class UpdateLinkImpl implements UpdateLink {

    public function __construct(private LinkRepository $linkRepository) {}

    public function execute(int $id, UpdateLinkInput $data): LinkOutput {
        try {
            $link = $this->linkRepository->find($id);
            $link = $link->copyWith($data->url);
            return LinkOutput::fromLink($this->linkRepository->update($link));
        } catch (ResourceNotFoundException $exception) {
            throw new BusinessException("Link não encontrado com o id $id!");
        } catch (\Exception $exception) {
            throw new BusinessException("Serviço indisponível!");
        }
    }
}