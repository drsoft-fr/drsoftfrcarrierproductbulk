<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Dto;

final class CarrierFilterDto
{
    private const MIN_VALID_ID = 1;

    public ?int $idReference = null;
    public ?string $name = null;
    public ?bool $isFree = null;
    public ?bool $deleted = null;
    public ?bool $active = null;
    public ?int $limit = null;
    public ?int $offset = null;

    public function __construct(array $data)
    {
        $this->idReference = $this->initializeIdValue($data, 'idReference');
        $this->name = $this->initializeStringValue($data, 'name');
        $this->isFree = $this->initializeBoolValue($data, 'isFree');
        $this->deleted = $this->initializeBoolValue($data, 'deleted');
        $this->active = $this->initializeBoolValue($data, 'active');
        $this->limit = $this->initializeIntValue($data, 'limit');
        $this->offset = $this->initializeIntValue($data, 'offset');
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
     * Initializes a nullable boolean from the data array.
     */
    private function initializeBoolValue(array $data, string $key): ?bool
    {
        return isset($data[$key]) && "" !== $data[$key] ? (bool)$data[$key] : null;
    }
}