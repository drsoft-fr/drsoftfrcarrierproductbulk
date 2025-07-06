<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Query;

use DrSoftFr\Module\CarrierProductBulk\Application\Dto\ProductFilterDto;

final class CountProductsQuery
{
    public ProductFilterDto $filter;

    public function __construct(ProductFilterDto $filter)
    {
        $this->filter = $filter;
    }
}
