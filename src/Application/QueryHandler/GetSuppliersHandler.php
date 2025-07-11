<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\QueryHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Dto\SupplierDto;
use DrSoftFr\Module\CarrierProductBulk\Application\Query\GetSuppliersQuery;
use DrSoftFr\Module\CarrierProductBulk\Domain\Repository\SupplierRepositoryInterface;

final class GetSuppliersHandler
{
    private SupplierRepositoryInterface $repository;

    public function __construct(SupplierRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handle(GetSuppliersQuery $query): array
    {
        $suppliers = $this->repository->getSuppliers();

        return array_map(function ($supplier) {
            return new SupplierDto($supplier['supplier_id'], $supplier['name']);
        }, $suppliers);
    }
}
