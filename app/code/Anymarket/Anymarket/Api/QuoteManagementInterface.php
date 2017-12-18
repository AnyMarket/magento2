<?php


namespace Anymarket\Anymarket\Api;

interface QuoteManagementInterface
{
    /**
     * PUT items price
     * @param int $idCart
     * @param \Anymarket\Anymarket\Api\Data\AnymarketCartItemsInterface $quote
     * @return string
     */
    public function putItemsPrice(
        $idCart,
       \Anymarket\Anymarket\Api\Data\AnymarketCartItemsInterface $quote
    );

    /**
     * PUT shipping amount
     * @param int $idCart
     * @param float $quote
     * @return string
     */
    public function putShippingAmount(
        $idCart,
        $shipAmount
    );
}
