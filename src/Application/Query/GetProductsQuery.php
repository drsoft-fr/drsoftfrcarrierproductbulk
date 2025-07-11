<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Query;

use DrSoftFr\Module\CarrierProductBulk\Application\Dto\ProductFilterDto;

final class GetProductsQuery
{
    public ProductFilterDto $filter;

    public function __construct(ProductFilterDto $filter)
    {
        $this->filter = $filter;
    }
}
