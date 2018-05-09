<?php
namespace Anymarket\Anymarket\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;

class ProductStockSave implements ObserverInterface
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
    ) {
        $this->_objectManager = $objectManager;
    }

    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $helper = $this->_objectManager->create('Anymarket\Anymarket\Helper\Data');

        $enabled = $helper->getGeneralConfig('anyConfig/general/enable');
        $canSyncProduct = $helper->getGeneralConfig('anyConfig/support/create_order_in_anymarket');
        if($enabled == "1" && $canSyncProduct == "0"){
            $item = $observer->getEvent()->getItem();
            $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($item->getProductId());

            $oi = $helper->getGeneralConfig('anyConfig/general/oi');
            $host = $helper->getGeneralConfig('anyConfig/general/host');

            $host = $host."/public/api/anymarketcallback/stockPrice/".$oi."/".$product->getSku();

            $helper->doCallAnymarket($host);
        }
        return $this;
    }
}
