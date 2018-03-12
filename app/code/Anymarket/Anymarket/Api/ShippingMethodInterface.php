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
     * @return array
     */
    public function getShippingMethods();
}
