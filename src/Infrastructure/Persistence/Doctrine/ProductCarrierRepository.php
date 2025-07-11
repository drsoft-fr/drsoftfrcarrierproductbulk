<?php

namespace DrSoftFr\Module\CarrierProductBulk\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;

final class ProductCarrierRepository
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

    public function addCarrierToProduct(int $carrierReferenceId, int $productId): void
    {
        $this->connection->insert(
            $this->tablePrefix . 'product_carrier',
            [
                'id_product' => $productId,
                'id_carrier_reference' => $carrierReferenceId,
                'id_shop' => $this->contextShopId,
            ],
        );
    }

    public function removeCarriersFromProduct(array $carrierReferenceIds, int $productId): void
    {
        $this->connection->executeStatement(
            'DELETE FROM ' . $this->tablePrefix . 'product_carrier WHERE id_product = :productId AND id_carrier_reference IN (:carrierIds)',
            [
                'productId' => $productId,
                'carrierIds' => $carrierReferenceIds,
            ],
            [
                'carrierIds' => Connection::PARAM_INT_ARRAY,
            ]
        );
    }
}
