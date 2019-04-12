<?php


namespace Anymarket\Anymarket\Api;

/**
 * Interface retrieving payment methods
 *
 * @api
 */

interface PaymentMethodInterface
{
    /**
     * Get payment methods
     *
     * @return mixed[]
     */
    public function getPaymentMethods();
}
