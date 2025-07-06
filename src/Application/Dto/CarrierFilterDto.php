<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Dto;

final class CarrierFilterDto
{
    public ?int $idReference = null;
    public ?string $name = null;
    public ?bool $isFree = null;
    public ?bool $deleted = null;
    public ?bool $active = null;
    public ?int $limit = null;
    public ?int $offset = null;

    public function __construct(array $data)
    {
        $this->idReference = isset($data['idReference']) && 0 < (int)$data['idReference'] ? (int)$data['idReference'] : null;
        $this->name = false === empty($data['name']) ? strip_tags($data['name']) : null;
        $this->isFree = isset($data['isFree']) && "" !== $data['isFree'] ? (bool)$data['isFree'] : null;
        $this->deleted = isset($data['deleted']) && "" !== $data['deleted'] ? (bool)$data['deleted'] : null;
        $this->active = isset($data['active']) && "" !== $data['active'] ? (bool)$data['active'] : null;
        $this->limit = isset($data['limit']) && "" !== $data['limit'] ? (int)$data['limit'] : null;
        $this->offset = isset($data['offset']) && "" !== $data['offset'] ? (int)$data['offset'] : null;
    }
}