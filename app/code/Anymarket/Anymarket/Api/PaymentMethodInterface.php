<?php


namespace Anymarket\Anymarket\Api;

/**
 * Interface retrieving shipping methods
 *
 * @api
 */

interface PaymentMethodInterface
{
    /**
     * Get payment methods
     *
     * @return array
     */
    public function getPaymentMethods();
}
