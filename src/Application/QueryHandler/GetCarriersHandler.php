<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetCarriersQuery;
use DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine\CarrierRepository;

final class GetCarriersHandler
{
    private CarrierRepository $repository;

    public function __construct(CarrierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetCarriersQuery $query): array
    {
        return $this->repository->findByFilter($query->filter);
    }
}
