<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Dto;

final class ProductFilterDto
{
    private const MIN_VALID_ID = 1;
    private const DEFAULT_LIMIT = 20;
    private const DEFAULT_PAGE = 1;

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
    public ?array $idCarrier = [];
    public ?string $carrierAssociation = null;
    public int $page = 1;
    public int $limit = 20;
    public int $offset = 0;

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
        $this->idCarrier = $this->hydrateArrayId($data['idCarrier'] ?? []);
        $this->carrierAssociation = $this->initializeCarrierAssociationValue($data);
        $this->page = $this->initializePageOrLimitValue($data, 'page', self::DEFAULT_PAGE);
        $this->limit = $this->initializePageOrLimitValue($data, 'limit', self::DEFAULT_LIMIT);
        $this->offset = $this->initializeOffsetValue() ?? 0;
    }

    public function toArray(): array
    {
        return [
            'idProduct' => $this->idProduct,
            'name' => $this->name,
            'reference' => $this->reference,
            'supplierReference' => $this->supplierReference,
            'idCategoryDefault' => $this->idCategoryDefault,
            'idCategory' => $this->idCategory,
            'idSupplier' => $this->idSupplier,
            'idManufacturer' => $this->idManufacturer,
            'weightMin' => $this->weightMin,
            'weightMax' => $this->weightMax,
            'active' => $this->active,
            'idCarrier' => $this->idCarrier,
            'carrierAssociation' => $this->carrierAssociation,
            'page' => $this->page,
            'limit' => $this->limit,
            'offset' => $this->offset,
        ];
    }

    /**
     * Initializes the page or limit value based on the provided data and a default value.
     */
    private function initializePageOrLimitValue(array $data, string $key, int $default): ?int
    {
        return false === empty($data[$key]) ? max(1, (int)$data[$key]) : $default;
    }

    /**
     * Initializes the offset value based on the current page and limit settings.
     */
    private function initializeOffsetValue(): ?int
    {
        return ($this->page - 1) * $this->limit;
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

    /**
     * Initializes the carrier association filter: 'with' or 'without'.
     */
    private function initializeCarrierAssociationValue(array $data): ?string
    {
        $value = $data['carrierAssociation'] ?? null;

        return in_array($value, ['with', 'without'], true) ? $value : null;
    }
}
