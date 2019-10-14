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
     * @return mixed[]
     */
    public function getPaymentMethods();
}
