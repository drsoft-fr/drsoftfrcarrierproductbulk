<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Dto;

final class CarrierFilterDto
{
    public ?int $idReference = null;
    public ?string $name = null;
    public ?bool $isFree = null;
    public ?bool $deleted = null;
    public ?bool $active = null;

    public function __construct(array $data)
    {
        $this->idReference = isset($data['idReference']) ? (int) $data['idReference'] : null;
        $this->name = $data['name'] ?? null;
        $this->isFree = isset($data['isFree']) ? (bool) $data['isFree'] : null;
        $this->deleted = isset($data['deleted']) ? (bool) $data['deleted'] : null;
        $this->active = isset($data['active']) ? (bool) $data['active'] : null;
    }
}