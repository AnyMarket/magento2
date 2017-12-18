<?php
namespace Anymarket\Anymarket\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;

class CollectTotalsAfter implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $totals = $observer->getData('total');
        $quote  = $observer->getData('quote');

        $shipPrefix = $quote->getShippingAddress()->getPrefix();
        $pos = strpos($shipPrefix, 'ANYMARKET');
        if ($pos === false) {
            return $this;
        }

        //grand total
        $quoteGrandTotal     = $quote->getGrandTotal();
        $quoteBaseGrandTotal = $quote->getBaseGrandTotal();

        $totals->setGrandTotal($quoteGrandTotal);
        $totals->setBaseGrandTotal($quoteBaseGrandTotal);

        //shipp amount
        $shipAmount     = $quote->getShippingAddress()->getShippingAmount();
        $shipBaseAmount = $quote->getShippingAddress()->getBaseShippingAmount();

        $totals->setShippingAmount($shipAmount);
        $totals->setBaseShippingAmount($shipBaseAmount);
        return $this;

    }
}
