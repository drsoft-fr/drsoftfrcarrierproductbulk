<?php

namespace DrSoftFr\Module\CarrierProductBulk\Domain\Service;

use DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine\ProductCarrierRepository;

final class CarrierProductService
{
    private ProductCarrierRepository $productCarrierRepository;

    public function __construct(ProductCarrierRepository $productCarrierRepository)
    {
        $this->productCarrierRepository = $productCarrierRepository;
    }

    public function addCarriersToProducts(array $carrierIds, array $productIds): void
    {
        foreach ($productIds as $productId) {
            foreach ($carrierIds as $carrierReferenceId) {
                $this->productCarrierRepository->addCarrierToProduct($carrierReferenceId, $productId);
            }
        }
    }

    public function removeCarriersFromProducts(array $carrierIds, array $productIds): void
    {
        foreach ($productIds as $productId) {
            $this->productCarrierRepository->removeCarriersFromProduct($carrierIds, $productId);
        }
    }
}
