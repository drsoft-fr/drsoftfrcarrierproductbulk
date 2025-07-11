<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Dto\ManufacturerDto;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetManufacturersQuery;
use DrSoftFr\Module\CarrierProductBulk\Domain\Repository\ManufacturerRepositoryInterface;

final class GetManufacturersHandler
{
    private ManufacturerRepositoryInterface $repository;

    public function __construct(ManufacturerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetManufacturersQuery $query): array
    {
        $manufacturers = $this->repository->getManufacturers();

        return array_map(function ($manufacturer) {
            return new ManufacturerDto($manufacturer['manufacturer_id'], $manufacturer['name']);
        }, $manufacturers);
    }
}
