<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetSuppliersQuery;
use PrestaShopBundle\Entity\Repository\SupplierRepository;

final class GetSuppliersHandler
{
    private SupplierRepository $repository;

    public function __construct(SupplierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetSuppliersQuery $query): array
    {
        return $this->repository->getSuppliers();
    }
}
