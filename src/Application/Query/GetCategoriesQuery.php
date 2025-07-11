<?php

namespace DrSoftFr\Module\CarrierProductBulk\Application\Query;

final class GetCategoriesQuery
{
    /**
     * @var bool
     */
    private bool $tree;

    public function __construct(bool $tree)
    {
        $this->assertIsBool($tree);
        $this->tree = $tree;
    }

    /**
     * @return bool
     */
    public function getTree(): bool
    {
        return $this->tree;
    }

    /**
     * Validates that value is of type boolean
     *
     * @param mixed $value
     *
     * @throws \InvalidArgumentException
     */
    private function assertIsBool($value)
    {
        if (!is_bool($value)) {
            throw new \InvalidArgumentException(sprintf('Tree must be of type bool, but given %s', var_export($value, true)));
        }
    }
}
