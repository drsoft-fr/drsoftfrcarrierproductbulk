<?php

namespace DrSoftFr\Module\CarrierProductBulk\Domain\Service;

use DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine\ProductCarrierRepository;

final class CarrierProductService
{
    private ProductCarrierRepository $repository;

    public function __construct(ProductCarrierRepository $repository)
    {
        $this->repository = $repository;
    }

    public function addCarriersToProducts(array $carrierIds, array $productIds): void
    {
        foreach ($productIds as $productId) {
            $this->repository->removeCarriersFromProduct($carrierIds, $productId);

            foreach ($carrierIds as $carrierId) {
                $this->repository->addCarrierToProduct($carrierId, $productId);
            }
        }
    }

    public function removeCarriersFromProducts(array $carrierIds, array $productIds): void
    {
        foreach ($productIds as $productId) {
            $this->repository->removeCarriersFromProduct($carrierIds, $productId);
        }
    }
}
