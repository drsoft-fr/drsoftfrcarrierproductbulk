<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Dto\CategoryDto;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetCategoriesQuery;
use DrSoftFr\Module\CarrierProductBulk\Domain\Repository\CategoryRepositoryInterface;

final class GetCategoriesHandler
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetCategoriesQuery $query): array
    {
        $categories = $this->repository->getCategories($query->getTree());

        return array_map(function ($category) {
            return new CategoryDto($category['id_category'], $category['name']);
        }, $categories);
    }
}
