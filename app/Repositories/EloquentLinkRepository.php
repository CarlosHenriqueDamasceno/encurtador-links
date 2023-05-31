<?php

namespace App\Repositories;

use App\Business\Link\Domain\Link;
use App\Business\Link\Port\LinkRepository;
use App\Models\Link as LinkModel;

class EloquentLinkRepository implements LinkRepository {

    public function create(Link $link): Link {
        $model = new LinkModel([
            'url' => $link->url,
            'slug' => $link->slug
        ]);
        $model->save();
        $model->refresh();
        return $link->copyWith(id: $model->id);
    }

    public function getAll(int $pageSize, int $page): array {
        $builder = LinkModel::query();
        $paginator = $builder->paginate(perPage: $pageSize, page: $page);
        $collection = $paginator->getCollection();
        $new_collection = $collection->map(function ($item) {
            return Link::buildExistentLink($item->id, $item->url, $item->slug);
        });
        return [
            'records' => $new_collection->toArray(),
            'metadata' => [
                'total' => $paginator->total(),
                'per_page' => $pageSize,
                'page' => $page
            ]
        ];
    }

    public function find(int $id): Link {
        $model = LinkModel::findOrFail($id);
        return Link::buildExistentLink($model->id, $model->url, $model->slug);
    }

    public function searchBySlug(string $slug): Link|null {
        $model = LinkModel::whereSlug($slug)->first();
        return $model ? Link::buildExistentLink(
            $model->id, $model->url, $model->slug
        ) : null;
    }

    public function update(Link $link): Link {
        $model = LinkModel::findOrFail($link->id);
        $model->url = $link->url;
        $model->slug = $link->slug;
        $model->save();
        return $link;
    }

    public function delete(int $id): void {
        LinkModel::destroy($id);
    }
}