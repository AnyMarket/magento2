<?php


namespace Anymarket\Anymarket\Api;

interface OrderManagementInterface
{


    /**
     * POST for order api
     * @param mixed $order
     * @return string
     */
    public function postOrder($order);
}
