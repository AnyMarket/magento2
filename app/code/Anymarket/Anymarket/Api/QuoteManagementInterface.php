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
     * @param float $shipAmount
     * @return string
     */
    public function putShippingAmount(
        $idCart,
        $shipAmount
    );

    /**
     * PUT TAX amount
     * @param int $idCart
     * @param float $interestValue
     * @return string
     */
    public function putInterestValue(
        $idCart,
        $interestValue
    );

}
