<?php

namespace DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine;

use DrSoftFr\Module\CarrierProductBulk\Domain\Repository\SupplierRepositoryInterface;
use PrestaShopBundle\Entity\Repository\SupplierRepository as PrestaShopSupplierRepository;

final class SupplierRepository implements SupplierRepositoryInterface
{
    private PrestaShopSupplierRepository $repository;

    public function __construct(PrestaShopSupplierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getSuppliers(): mixed
    {
        return $this->repository->getSuppliers();
    }
}
