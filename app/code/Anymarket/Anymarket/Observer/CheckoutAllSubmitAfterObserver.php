<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Anymarket\Anymarket\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;


class CheckoutAllSubmitAfterObserver implements ObserverInterface
{

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var \Anymarket\Anymarket\Model\Anymarketfeed 
     */
    protected $feed;

    /**
     * @var \Anymarket\Anymarket\Helper\Data
     */
    protected $helper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param ProductFactory $productFactory
     * @param Anymarketfeed $feed
     * @param Data $helper
     */
    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Anymarket\Anymarket\Model\Anymarketfeed $feed,
        \Anymarket\Anymarket\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->productFactory = $productFactory;
        $this->feed = $feed;
        $this->helper = $helper;
        $this->storeManager = $storeManager;
    }

    /**
     * Subtract qtys of quote item products after multishipping checkout
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        $quote = $observer->getEvent()->getQuote();

        foreach ($quote->getAllItems() as $item) {

            $storeId = $item->getStoreId();
            if(!$storeId){
                $storeId = 1;
            }
            
            foreach ($this->storeManager->getStores() as $storeId => $storeData) {
                $enabled = $this->helper->getGeneralConfig('anyConfig/general/enable', $storeId);
                $canSyncOrder = $this->helper->getGeneralConfig('anyConfig/support/create_order_in_anymarket', $storeId);
                if ($enabled == "1" && $canSyncOrder == "0") {
                    $productId = $this->productFactory->create()->getIdBySku($item->getSku());
                    $product = $this->productFactory->create()->load($productId);

                    $oi = $this->helper->getGeneralConfig('anyConfig/general/oi', $storeId);
                    if ($feed = $this->helper->getGeneralConfig('anyConfig/general/feedStock', $storeId) == "1") {
                        $this->saveFeed($product->getSku(), "0", $oi);
                    } else {
                        $host = $this->helper->getGeneralConfig('anyConfig/general/host', $storeId);
                        $host = $host . "/public/api/anymarketcallback/stockPrice";
                        $this->helper->doCallAnymarket($host, $oi, "", $product->getSku());
                    }
                }
            }
        }
        return $this;
    }

    private function saveFeed($id, $type, $oi)
    {
        $collection = $this->feed->getCollection();
        $collection->addFieldToFilter("id_item",$id);
        $collection->addFieldToFilter("oi",$oi);
        $collection->addFieldToFilter("type",$type);
        $collection->addFieldToFilter(
            'updated_at',
            array('null' => true)
        );
        $collection->addFieldToFilter(
            'created_at',
            array('null' => true)
        );

        if($collection->getSize() < 1 ){
            $this->feed->setIdItem($id);
            $this->feed->setType($type);
            $this->feed->setOi($oi);
            $this->feed->save();
        }
    }

}
