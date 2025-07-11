<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Query\CountCarriersQuery;
use DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine\CarrierRepository;

final class CountCarriersHandler
{
    private CarrierRepository $repository;

    public function __construct(CarrierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CountCarriersQuery $query): int
    {
        return $this->repository->countByFilter($query->filter);
    }
}
