<?php

namespace DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine;

use DrSoftFr\Module\CarrierProductBulk\Domain\Repository\ManufacturerRepositoryInterface;
use PrestaShopBundle\Entity\Repository\ManufacturerRepository as PrestaShopManufacturerRepository;

final class ManufacturerRepository implements ManufacturerRepositoryInterface
{
    private PrestaShopManufacturerRepository $repository;

    public function __construct(PrestaShopManufacturerRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getManufacturers(): mixed
    {
        return $this->repository->getManufacturers();
    }
}
