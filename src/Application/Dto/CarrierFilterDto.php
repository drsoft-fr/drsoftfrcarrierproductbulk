<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Dto;

final class CarrierFilterDto
{
    private const MIN_VALID_ID = 1;
    private const DEFAULT_LIMIT = 20;
    private const DEFAULT_PAGE = 1;

    public ?int $idReference = null;
    public ?string $name = null;
    public ?bool $isFree = null;
    public ?bool $deleted = null;
    public ?bool $active = null;
    public int $page = 1;
    public int $limit = 20;
    public int $offset = 0;

    public function __construct(array $data)
    {
        $this->idReference = $this->initializeIdValue($data, 'idReference');
        $this->name = $this->initializeStringValue($data, 'name');
        $this->isFree = $this->initializeBoolValue($data, 'isFree');
        $this->deleted = $this->initializeBoolValue($data, 'deleted');
        $this->active = $this->initializeBoolValue($data, 'active');
        $this->page = $this->initializePageOrLimitValue($data, 'page', self::DEFAULT_PAGE);
        $this->limit = $this->initializePageOrLimitValue($data, 'limit', self::DEFAULT_LIMIT);
        $this->offset = $this->initializeOffsetValue() ?? 0;
    }

    public function toArray(): array
    {
        return [
            'idReference' => $this->idReference,
            'name' => $this->name,
            'isFree' => $this->isFree,
            'deleted' => $this->deleted,
            'active' => $this->active,
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