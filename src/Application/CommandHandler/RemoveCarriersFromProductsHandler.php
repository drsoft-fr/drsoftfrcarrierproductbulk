<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\CommandHandler;

use DrSoftFr\Module\CarrierProductBulk\Application\Command\RemoveCarriersFromProductsCommand;
use DrSoftFr\Module\CarrierProductBulk\Domain\Service\CarrierProductService;

final class RemoveCarriersFromProductsHandler
{
    private CarrierProductService $service;

    public function __construct(CarrierProductService $service)
    {
        $this->service = $service;
    }

    public function handle(RemoveCarriersFromProductsCommand $command): void
    {
        $this->service->removeCarriersFromProducts($command->carrierIds, $command->productIds);
    }
}
