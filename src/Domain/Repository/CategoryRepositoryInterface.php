<?php

namespace DrSoftFr\Module\CarrierProductBulk\Domain\Repository;

interface CategoryRepositoryInterface
{
    public function getCategories(bool $tree): mixed;
}
