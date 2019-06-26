<?php


namespace Anymarket\Anymarket\Api;

/**
 * Interface retrieving regions 
 *
 * @api
 */

interface RegionInterface
{
    /**
     * Get shipping regions
     * @param string $countryCode
     * @return mixed[]
     */
    public function getRegions($countryCode);
}
