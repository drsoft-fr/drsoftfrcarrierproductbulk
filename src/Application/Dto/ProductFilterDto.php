<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Dto;

final class ProductFilterDto
{
    private const MIN_VALID_ID = 1;

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
        $this->idProduct = isset($data['idProduct']) && 0 < (int)$data['idProduct'] ? (int)$data['idProduct'] : null;
        $this->name = false === empty($data['name']) ? strip_tags($data['name']) : null;
        $this->reference = false === empty($data['reference']) ? strip_tags($data['reference']) : null;
        $this->supplierReference = false === empty($data['supplierReference']) ? strip_tags($data['supplierReference']) : null;
        $this->idCategoryDefault = $this->hydrateArrayId($data['idCategoryDefault'] ?? []);
        $this->idCategory = $this->hydrateArrayId($data['idCategory'] ?? []);
        $this->idSupplier = $this->hydrateArrayId($data['idSupplier'] ?? []);
        $this->idManufacturer = $this->hydrateArrayId($data['idManufacturer'] ?? []);
        $this->weightMin = isset($data['weightMin']) && "" !== $data['weightMin'] ? (float)$data['weightMin'] : null;
        $this->weightMax = isset($data['weightMax']) && "" !== $data['weightMax'] ? (float)$data['weightMax'] : null;
        $this->active = isset($data['active']) && "" !== $data['active'] ? (bool)$data['active'] : null;
        $this->limit = isset($data['limit']) && "" !== $data['limit'] ? (int)$data['limit'] : null;
        $this->offset = isset($data['offset']) && "" !== $data['offset'] ? (int)$data['offset'] : null;
    }

    /**
     * Filters and hydrates an array of IDs by removing invalid entries.
     *
     * @param array $inputArray Array of IDs to be filtered.
     * @return array Filtered array of valid IDs.
     */
    private function hydrateArrayId(array $inputArray): array
    {
        $filteredIds = array_filter($inputArray, fn($id) => $this->isValidId((int)$id));

        return array_map('intval', $filteredIds);
    }

    /**
     * Checks if an ID is valid.
     *
     * @param int $id The ID to validate.
     * @return bool True if the ID is valid, false otherwise.
     */
    private function isValidId(int $id): bool
    {
        return $id >= self::MIN_VALID_ID;
    }

}
