<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Dto;

final class SupplierDto
{
    public int $id;
    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
