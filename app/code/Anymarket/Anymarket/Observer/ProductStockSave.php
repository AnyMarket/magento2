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
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_objectManager = $objectManager;
        $this->storeManager = $storeManager;
    }

    private function saveFeed($id, $type, $oi)
    {
        $modelFeed = $this->_objectManager->create('Anymarket\Anymarket\Model\Anymarketfeed');
        $collection = $modelFeed->getCollection();
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
            $modelFeed->setIdItem($id);
            $modelFeed->setType($type);
            $modelFeed->setOi($oi);
            $modelFeed->save();
        }
        
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
        $itemOi = [];

        $helper = $this->_objectManager->create('Anymarket\Anymarket\Helper\Data');

        $enabled = $helper->getGeneralConfig('anyConfig/general/enable', $storeId);
        $canSyncOrder = $helper->getGeneralConfig('anyConfig/support/create_order_in_anymarket', $storeId);
        if ($enabled == "1" && $canSyncOrder == "0") {
            $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($item->getProductId());

            foreach ($product->getStoreIds() as $id => $storeId) {
                $enabled =  $helper->getGeneralConfig('anyConfig/general/enable', $storeId);
                $canSyncOrder =  $helper->getGeneralConfig('anyConfig/support/create_order_in_anymarket', $storeId);
                if ($enabled == "1" && $canSyncOrder == "0") {
                    
                    $oi =  $helper->getGeneralConfig('anyConfig/general/oi', $storeId);
                    $host =  $helper->getGeneralConfig('anyConfig/general/host', $storeId);
                    $host = $host . "/public/api/anymarketcallback/stockPrice";

                    if(!isset($itemOi[$oi])){
                        $itemOi[$oi] = [
                            "feed" =>  $helper->getGeneralConfig('anyConfig/general/feedStock', $storeId),
                            "host" => $host,
                            "itemId" => $product->getSku()
                        ];
                    }
                }
            }
            
            foreach($itemOi as $oi=>$item){
 
                if ($item["feed"] == "1") {
                    $this->saveFeed($product->getSku(), "0", $oi);
                } else {
                    $helper->doCallAnymarket($item["host"], $oi,"" ,$item["itemId"]);
                }
            }
        }
        
        return $this;
    }
}
