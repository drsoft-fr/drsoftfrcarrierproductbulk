<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\CommandHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Command\AddCarriersToProductsCommand;
use DrSoftFr\Module\CarrierProductBulk\Domain\Service\CarrierProductService;

final class AddCarriersToProductsHandler
{
    private CarrierProductService $service;

    public function __construct(CarrierProductService $service)
    {
        $this->service = $service;
    }

    public function handle(AddCarriersToProductsCommand $command): void
    {
        $this->service->addCarriersToProducts($command->carrierIds, $command->productIds);
    }
}
