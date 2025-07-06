<?php

namespace DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
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
        $qb = $this->connection->createQueryBuilder();
        $qb->select(
            'p.id_product',
            'pl.name',
            'p.reference',
            'p.supplier_reference',
            'p.id_category_default',
            'p.id_supplier',
            'p.id_manufacturer',
            'p.weight',
            'p.active',
            'p.visibility'
        )
            ->from(
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

        if ($filter->active !== null) {
            $qb->andWhere('p.active = :active')
                ->setParameter('active', $filter->active);
        }

        return $qb->execute()->fetchAllAssociative();
    }
}
