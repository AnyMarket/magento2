<?php

namespace Anymarket\Anymarket\Model\Data;

class AnymarketCartItems implements \Anymarket\Anymarket\Api\Data\AnymarketCartItemsInterface
{

    /**
     * Quote
     *
     * item for anymarket method
     *
     * @var \Magento\Quote\Api\Data\CartItemInterface[]
     */
    protected $_quote = null;

    /**
     * items
     *
     * item for anymarket method
     *
     * @var \Magento\Quote\Api\Data\CartItemInterface[]
     */
    protected $_items = null;

    /**
     * @param \Magento\Framework\Model\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
    }


    /**
     * Returns quote
     *
     * @return \Magento\Quote\Api\Data\CartItemInterface[]|null
     */
    public function getQuote()
    {
        return $this->_quote;
    }

    /**
     * Sets quote
     *
     * @param \Magento\Quote\Api\Data\CartItemInterface[] $quote
     * @return $this
     */
    public function setQuote(array $quote = null)
    {
        $this->_quote = $quote;
        return $this;
    }

    /**
     * Returns items
     *
     * @return \Magento\Quote\Api\Data\CartItemInterface[]
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * Sets items
     *
     * @param \Magento\Quote\Api\Data\CartItemInterface[] $items
     * @return $this
     */
    public function setItems(array $items = null)
    {

        $this->_items = $items;
        return $this;
    }
}
