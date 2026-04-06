<?php

namespace DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use DrSoftFr\Module\CarrierProductBulk\Application\Dto\ProductFilterDto;

final class ProductRepository
{
    private Connection $connection;
    private int $contextLangId;
    private int $contextShopId;
    private string $tablePrefix;

    public function __construct(
        Connection $connection,
        int        $contextLangId,
        int        $contextShopId,
        string     $tablePrefix
    )
    {
        $this->connection = $connection;
        $this->contextLangId = $contextLangId;
        $this->contextShopId = $contextShopId;
        $this->tablePrefix = $tablePrefix;
    }

    public function findByFilter(ProductFilterDto $filter): array
    {
        $qb = $this->getQueryBuilder($filter);

        $qb->select(
            'p.id_product',
            'pl.name',
            'p.reference',
            'p.supplier_reference',
            'p.id_category_default',
            'p.id_supplier',
            'p.id_manufacturer',
            'p.weight',
            'p.width',
            'p.height',
            'p.depth',
            'p.active',
            'p.visibility'
        );

        $qb->addSelect(sprintf(
            '(SELECT GROUP_CONCAT(c2.name ORDER BY c2.name SEPARATOR \', \') FROM %sproduct_carrier pc2 INNER JOIN %scarrier c2 ON c2.id_reference = pc2.id_carrier_reference AND c2.deleted = 0 WHERE pc2.id_product = p.id_product AND pc2.id_shop = %d) AS carrier_names',
            $this->tablePrefix,
            $this->tablePrefix,
            $this->contextShopId
        ));

        if ($filter->limit !== null) {
            $qb->setMaxResults($filter->limit);
        }

        if ($filter->offset !== null) {
            $qb->setFirstResult($filter->offset);
        }

        return $qb->execute()->fetchAllAssociative();
    }


    public function countByFilter(ProductFilterDto $filter): int
    {
        $qb = $this->getQueryBuilder($filter);

        $qb->select('COUNT(DISTINCT p.id_product)');

        return (int)$qb->execute()->fetchOne();
    }

    private function getQueryBuilder(ProductFilterDto $filter): QueryBuilder
    {
        $qb = $this->connection->createQueryBuilder();
        $qb->from(
            $this->tablePrefix . 'product',
            'p'
        )
            ->innerJoin(
                'p',
                $this->tablePrefix . 'product_lang',
                'pl',
                'pl.id_product = p.id_product'
            )
            ->innerJoin(
                'p',
                $this->tablePrefix . 'product_shop',
                'ps',
                'ps.id_product = p.id_product'
            )
            ->andWhere('pl.id_lang = :contextLangId')
            ->andWhere('ps.id_shop = :contextShopId')
            ->setParameter('contextLangId', $this->contextLangId)
            ->setParameter('contextShopId', $this->contextShopId);

        if (!empty($filter->idCategory)) {
            $qb->innerJoin(
                'p',
                $this->tablePrefix . 'category_product',
                'cp',
                'cp.id_product = p.id_product'
            );
        }

        if ($filter->idProduct !== null) {
            $qb->andWhere('p.id_product = :idProduct')
                ->setParameter('idProduct', $filter->idProduct);
        }

        if ($filter->name) {
            $qb->andWhere('pl.name LIKE :name')
                ->setParameter('name', '%' . $filter->name . '%');
        }

        if ($filter->reference) {
            $qb->andWhere('p.reference LIKE :reference')
                ->setParameter('reference', '%' . $filter->reference . '%');
        }

        if ($filter->supplierReference) {
            $qb->andWhere('p.supplier_reference LIKE :supplierReference')
                ->setParameter('supplierReference', '%' . $filter->supplierReference . '%');
        }

        if (!empty($filter->idCategoryDefault)) {
            $qb->andWhere('p.id_category_default IN (:idCategoryDefault)')
                ->setParameter('idCategoryDefault', $filter->idCategoryDefault, Connection::PARAM_INT_ARRAY);
        }

        if (!empty($filter->idCategory)) {
            $qb->andWhere('cp.id_category IN (:idCategory)')
                ->setParameter('idCategory', $filter->idCategory, Connection::PARAM_INT_ARRAY);
        }

        if (!empty($filter->idSupplier)) {
            $qb->andWhere('p.id_supplier IN (:idSupplier)')
                ->setParameter('idSupplier', $filter->idSupplier, Connection::PARAM_INT_ARRAY);
        }

        if (!empty($filter->idManufacturer)) {
            $qb->andWhere('p.id_manufacturer IN (:idManufacturer)')
                ->setParameter('idManufacturer', $filter->idManufacturer, Connection::PARAM_INT_ARRAY);
        }

        if ($filter->weightMin !== null) {
            $qb->andWhere('p.weight >= :weightMin')
                ->setParameter('weightMin', $filter->weightMin);
        }

        if ($filter->weightMax !== null) {
            $qb->andWhere('p.weight <= :weightMax')
                ->setParameter('weightMax', $filter->weightMax);
        }

        if ($filter->widthMin !== null) {
            $qb->andWhere('p.width >= :widthMin')
                ->setParameter('widthMin', $filter->widthMin);
        }

        if ($filter->widthMax !== null) {
            $qb->andWhere('p.width <= :widthMax')
                ->setParameter('widthMax', $filter->widthMax);
        }

        if ($filter->heightMin !== null) {
            $qb->andWhere('p.height >= :heightMin')
                ->setParameter('heightMin', $filter->heightMin);
        }

        if ($filter->heightMax !== null) {
            $qb->andWhere('p.height <= :heightMax')
                ->setParameter('heightMax', $filter->heightMax);
        }

        if ($filter->depthMin !== null) {
            $qb->andWhere('p.depth >= :depthMin')
                ->setParameter('depthMin', $filter->depthMin);
        }

        if ($filter->depthMax !== null) {
            $qb->andWhere('p.depth <= :depthMax')
                ->setParameter('depthMax', $filter->depthMax);
        }

        if ($filter->active !== null) {
            $qb->andWhere('p.active = :active')
                ->setParameter('active', $filter->active);
        }

        if ($filter->carrierAssociation !== null) {
            $prefix = $this->tablePrefix;
            $shopId = (int) $this->contextShopId;
            $inClause = '';

            if (!empty($filter->idCarrier)) {
                $ids = implode(',', array_map('intval', $filter->idCarrier));
                $inClause = " AND pc_f.id_carrier_reference IN ({$ids})";
            }

            $existsSql = "EXISTS (SELECT 1 FROM {$prefix}product_carrier pc_f WHERE pc_f.id_product = p.id_product{$inClause} AND pc_f.id_shop = {$shopId})";

            if ($filter->carrierAssociation === 'without') {
                $qb->andWhere("NOT {$existsSql}");
            } else {
                $qb->andWhere($existsSql);
            }
        }

        return $qb;
    }
}
