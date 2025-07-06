<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Query;

use DrSoftFr\Module\CarrierProductBulk\Application\Dto\CarrierFilterDto;

final class GetCarriersQuery
{
    public CarrierFilterDto $filter;

    public function __construct(CarrierFilterDto $filter)
    {
        $this->filter = $filter;
    }
}
