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
}
