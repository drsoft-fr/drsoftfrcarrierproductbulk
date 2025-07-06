<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Dto;

final class ProductFilterDto
{
    public ?int $idProduct = null;
    public ?string $name = null;
    public ?string $reference = null;
    public ?string $supplierReference = null;
    public ?array $idCategoryDefault = [];
    public ?array $idCategory = [];
    public ?array $idSupplier = [];
    public ?array $idManufacturer = [];
    public ?float $weightMin = null;
    public ?float $weightMax = null;
    public ?bool $active = null;
    public ?int $limit = null;
    public ?int $offset = null;

    public function __construct(array $data)
    {
        $this->idProduct = isset($data['idProduct']) ? (int)$data['idProduct'] : null;
        $this->name = $data['name'] ?? null;
        $this->reference = $data['reference'] ?? null;
        $this->supplierReference = $data['supplierReference'] ?? null;
        $this->idCategoryDefault = $data['idCategoryDefault'] ?? [];
        $this->idCategory = $data['idCategory'] ?? [];
        $this->idSupplier = $data['idSupplier'] ?? [];
        $this->idManufacturer = $data['idManufacturer'] ?? [];
        $this->weightMin = isset($data['weightMin']) ? (float)$data['weightMin'] : null;
        $this->weightMax = isset($data['weightMax']) ? (float)$data['weightMax'] : null;
        $this->active = isset($data['active']) ? (bool)$data['active'] : null;
        $this->limit = isset($data['limit']) ? (int)$data['limit'] : null;
        $this->offset = isset($data['offset']) ? (int)$data['offset'] : null;
    }
}
