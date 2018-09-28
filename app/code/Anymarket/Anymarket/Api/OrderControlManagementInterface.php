<?php

namespace Anymarket\Anymarket\Api;

interface OrderControlManagementInterface
{
    /**
     * POST Order Control from Anymarket
     * @param \Anymarket\Anymarket\Api\Data\AnymarketOrderControlInterface $order
     * @return string
     */
    public function postOrderControl(
        \Anymarket\Anymarket\Api\Data\AnymarketOrderControlInterface $order
    );

    /**
     * Get Order Control from Anymarket
     * @param int $idAnymarket
     * @return array
     */
    public function getOrderByIdAnymarket($idAnymarket);

}