<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Query\CountProductsQuery;
use DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine\ProductRepository;

final class CountProductsHandler
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CountProductsQuery $query): int
    {
        return $this->repository->countByFilter($query->filter);
    }
}
