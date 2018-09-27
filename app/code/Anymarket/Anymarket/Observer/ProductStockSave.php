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
    )
    {
        $this->_objectManager = $objectManager;
    }

    private function saveFeed($id, $type, $oi)
    {
        $modelFeed = $this->_objectManager->create('Anymarket\Anymarket\Model\Anymarketfeed');
        $modelFeed->setIdItem($id);
        $modelFeed->setType($type);
        $modelFeed->setOi($oi);
        $modelFeed->save();
    }

    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $item = $observer->getEvent()->getItem();
        $storeId = $item->getStoreId();

        $helper = $this->_objectManager->create('Anymarket\Anymarket\Helper\Data');

        $enabled = $helper->getGeneralConfig('anyConfig/general/enable', $storeId);
        $canSyncOrder = $helper->getGeneralConfig('anyConfig/support/create_order_in_anymarket', $storeId);
        if ($enabled == "1" && $canSyncOrder == "0") {
            $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($item->getProductId());

            $oi = $helper->getGeneralConfig('anyConfig/general/oi', $storeId);
            if ($feed = $helper->getGeneralConfig('anyConfig/general/feedStock', $storeId) == "1") {
                $this->saveFeed($product->getSku(), "0", $oi);
            } else {
                $host = $helper->getGeneralConfig('anyConfig/general/host', $storeId);
                $host = $host . "/public/api/anymarketcallback/stockPrice/" . $oi . "/" . $product->getSku();
                $helper->doCallAnymarket($host);
            }
        }
        return $this;
    }
}
