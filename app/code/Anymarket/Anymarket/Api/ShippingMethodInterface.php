<?php


namespace Anymarket\Anymarket\Api;

/**
 * Interface retrieving shipping methods
 *
 * @api
 */

interface ShippingMethodInterface
{
    /**
     * Get shipping methods
     *
     * @return mixed[]
     */
    public function getShippingMethods();
}
