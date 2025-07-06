<?php

namespace DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use DrSoftFr\Module\CarrierProductBulk\Application\Dto\CarrierFilterDto;

final class CarrierRepository
{
    private Connection $connection;
    private int $contextShopId;
    private string $tablePrefix;

    public function __construct(
        Connection $connection,
        int        $contextShopId,
        string     $tablePrefix
    )
    {
        $this->connection = $connection;
        $this->contextShopId = $contextShopId;
        $this->tablePrefix = $tablePrefix;
    }

    public function findByFilter(CarrierFilterDto $filter): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->select('*')
            ->from(
                $this->tablePrefix . 'carrier',
                'c'
            )
            ->innerJoin(
                'c',
                $this->tablePrefix . 'carrier_shop',
                'cs',
                'cs.id_carrier = c.id_carrier'
            )
            ->andWhere('cs.id_shop = :contextShopId')
            ->setParameter('contextShopId', $this->contextShopId);

        if ($filter->idReference !== null) {
            $qb->andWhere('c.id_reference = :idReference')->setParameter('idReference', $filter->idReference);
        }

        if ($filter->name) {
            $qb->andWhere('c.name LIKE :name')->setParameter('name', '%' . $filter->name . '%');
        }

        if ($filter->isFree !== null) {
            $qb->andWhere('c.is_free = :isFree')->setParameter('isFree', $filter->isFree);
        }

        if ($filter->deleted !== null) {
            $qb->andWhere('c.deleted = :deleted')->setParameter('deleted', $filter->deleted);
        }

        if ($filter->active !== null) {
            $qb->andWhere('c.active = :active')->setParameter('active', $filter->active);
        }

        return $qb->execute()->fetchAllAssociative();
    }
}
