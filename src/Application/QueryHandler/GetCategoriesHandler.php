<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetCategoriesQuery;
use PrestaShopBundle\Entity\Repository\CategoryRepository;

final class GetCategoriesHandler
{
    private CategoryRepository $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetCategoriesQuery $query): array
    {
        return $this->repository->getCategories($query->getTree());
    }
}
