<?php

namespace DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine;

use DrSoftFr\Module\CarrierProductBulk\Domain\Repository\CategoryRepositoryInterface;
use PrestaShopBundle\Entity\Repository\CategoryRepository as PrestaShopCategoryRepository;

final class CategoryRepository implements CategoryRepositoryInterface
{
    private PrestaShopCategoryRepository $repository;

    public function __construct(PrestaShopCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCategories(bool $tree): mixed
    {
        return $this->repository->getCategories($tree);
    }
}
