<?php

namespace Anymarket\Anymarket\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;

class ProductSaveAfter implements ObserverInterface
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
        $product = $observer->getEvent()->getProduct();
        $storeId = $product->getStoreId();

        $helper = $this->_objectManager->create('Anymarket\Anymarket\Helper\Data');

        $enabled = $helper->getGeneralConfig('anyConfig/general/enable', $storeId);
        $canSyncProduct = $helper->getGeneralConfig('anyConfig/support/create_product_in_anymarket', $storeId);
        if ($enabled == "1" && $canSyncProduct == "1") {
            $attrIntegration = $helper->getGeneralConfig('anyConfig/support/attr_integration_anymarket', $storeId);
            if ($product->getData($attrIntegration) != "1") {
                return $this;
            }

            $oi = $helper->getGeneralConfig('anyConfig/general/oi', $storeId);
            if ($feed = $helper->getGeneralConfig('anyConfig/general/feedProduct', $storeId) == "1") {
                $this->saveFeed($product->getSku(), "2", $oi);
            } else {
                $host = $helper->getGeneralConfig('anyConfig/general/host', $storeId);
                $host = $host . "/public/api/anymarketcallback/product/" . $oi . "/MAGENTO_2/" . ScopeInterface::SCOPE_STORE . "/" . $product->getSku();
                $helper->doCallAnymarket($host);
            }
        }
        return $this;
    }
}
