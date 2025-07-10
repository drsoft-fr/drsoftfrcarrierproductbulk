<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetManufacturersQuery;
use PrestaShopBundle\Entity\Repository\ManufacturerRepository;

final class GetManufacturersHandler
{
    private ManufacturerRepository $repository;

    public function __construct(ManufacturerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetManufacturersQuery $query): array
    {
        return $this->repository->getManufacturers();
    }
}
