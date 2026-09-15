<?php

namespace DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
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
        $qb = $this->getQueryBuilder($filter);

        $qb->select('c.*');

        if ($filter->limit !== null) {
            $qb->setMaxResults($filter->limit);
        }

        if ($filter->offset !== null) {
            $qb->setFirstResult($filter->offset);
        }

        return $qb->execute()->fetchAllAssociative();
    }

    public function countByFilter(CarrierFilterDto $filter): int
    {
        $qb = $this->getQueryBuilder($filter);

        $qb->select('COUNT(DISTINCT c.id_carrier)');

        return (int)$qb->execute()->fetchOne();
    }

    private function getQueryBuilder(CarrierFilterDto $filter): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->from(
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
            $qb->andWhere('c.deleted = :deleted')->setParameter('deleted', $filter->deleted ? 1 : 0);
        }

        if ($filter->active !== null) {
            $qb->andWhere('c.active = :active')->setParameter('active', $filter->active ? 1 : 0);
        }

        $qb->orderBy('c.name', 'ASC');

        return $qb;
    }
}
