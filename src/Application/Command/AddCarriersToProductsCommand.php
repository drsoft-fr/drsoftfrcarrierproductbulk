<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Command;

final class AddCarriersToProductsCommand
{
    public array $carrierIds;
    public array $productIds;

    public function __construct(array $carrierIds, array $productIds)
    {
        $this->carrierIds = $carrierIds;
        $this->productIds = $productIds;
    }
}
