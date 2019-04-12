<?php

namespace Anymarket\Anymarket\Api;

interface FeedManagementInterface
{

    /**
     * Get Order Control from Anymarket
     * @param int $typeFeed
     * @return mixed[]
     */
    public function getFeedByType($typeFeed);

}