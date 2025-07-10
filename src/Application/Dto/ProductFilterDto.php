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
        $this->idProduct = $this->initializeIdValue($data, 'idProduct');
        $this->name = $this->initializeStringValue($data, 'name');
        $this->reference = $this->initializeStringValue($data, 'reference');
        $this->supplierReference = $this->initializeStringValue($data, 'supplierReference');
        $this->idCategoryDefault = $this->hydrateArrayId($data['idCategoryDefault'] ?? []);
        $this->idCategory = $this->hydrateArrayId($data['idCategory'] ?? []);
        $this->idSupplier = $this->hydrateArrayId($data['idSupplier'] ?? []);
        $this->idManufacturer = $this->hydrateArrayId($data['idManufacturer'] ?? []);
        $this->weightMin = $this->initializeFloatValue($data, 'weightMin');
        $this->weightMax = $this->initializeFloatValue($data, 'weightMax');
        $this->active = $this->initializeBoolValue($data, 'active');
        $this->limit = $this->initializeIntValue($data, 'limit');
        $this->offset = $this->initializeIntValue($data, 'offset');
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

    /**
     * Initializes and retrieves an integer ID value from provided data if valid.
     */
    private function initializeIdValue(array $data, string $key): ?int
    {
        return isset($data[$key]) && self::MIN_VALID_ID <= (int)$data[$key] ? (int)$data[$key] : null;
    }

    /**
     * Initializes a nullable integer from the data array.
     */
    private function initializeIntValue(array $data, string $key): ?int
    {
        return isset($data[$key]) && "" !== $data[$key] ? (int)$data[$key] : null;
    }

    /**
     * Initializes a nullable string from the data array.
     */
    private function initializeStringValue(array $data, string $key): ?string
    {
        return !empty($data[$key]) ? strip_tags($data[$key]) : null;
    }

    /**
     * Initializes a nullable float from the data array.
     */
    private function initializeFloatValue(array $data, string $key): ?float
    {
        return isset($data[$key]) && "" !== $data[$key] ? (float)$data[$key] : null;
    }

    /**
     * Initializes a nullable boolean from the data array.
     */
    private function initializeBoolValue(array $data, string $key): ?bool
    {
        return isset($data[$key]) && "" !== $data[$key] ? (bool)$data[$key] : null;
    }
}
