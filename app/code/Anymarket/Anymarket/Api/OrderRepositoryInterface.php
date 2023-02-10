<?php

namespace Anymarket\Anymarket\Api;

interface OrderRepositoryInterface
{
    /**
     * Performs persist operations for a specified order.
     *
     * @param \Magento\Sales\Api\Data\OrderInterface $entity The order ID.
     * @return \Magento\Sales\Api\Data\OrderInterface Order interface.
     */
    public function save(\Magento\Sales\Api\Data\OrderInterface $entity);
}
