<?php

namespace Anymarket\Anymarket\Api\Data;

/**
 * Interface AnymarketCartItemsInterface
 * @api
 */
interface AnymarketCartItemsInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const KEY_QUOTE = 'quote';

    const KEY_ITEMS = 'items';

    /**
     * Returns quote
     *
     * @return \Magento\Quote\Api\Data\CartItemInterface[]
     */
    public function getQuote();

    /**
     * Sets items
     *
     * @param \Magento\Quote\Api\Data\CartItemInterface[] $quote
     * @return $this
     */
    public function setQuote(array $quote = null);


    /**
     * Returns items
     *
     * @return \Magento\Quote\Api\Data\CartItemInterface[]
     */
    public function getItems();

    /**
     * Sets items
     *
     * @param \Magento\Quote\Api\Data\CartItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null);


}