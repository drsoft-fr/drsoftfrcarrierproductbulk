<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetProductsQuery;
use DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine\ProductRepository;

final class GetProductsHandler
{
    private ProductRepository $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetProductsQuery $query): array
    {
        return $this->repository->findByFilter($query->filter);
    }
}
