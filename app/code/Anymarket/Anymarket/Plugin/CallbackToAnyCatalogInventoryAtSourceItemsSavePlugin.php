<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Anymarket\Anymarket\Plugin;

use Magento\InventoryApi\Api\Data\SourceItemInterface;
use Magento\InventoryApi\Api\SourceItemsSaveInterface;
use Magento\InventoryCatalog\Model\SourceItemsSaveSynchronization\SetDataToLegacyCatalogInventory;
use Magento\Framework\App\ObjectManager;

/**
 * Synchronization between legacy Stock Items and saved Source Items
 */
class CallbackToAnyCatalogInventoryAtSourceItemsSavePlugin
{
    /**
     * @var DefaultSourceProviderInterface
     */
    private $defaultSourceProvider;

    /**
     * @var IsSourceItemManagementAllowedForProductTypeInterface
     */
    private $isSourceItemsAllowedForProductType;

    /**
     * @var GetProductTypesBySkusInterface
     */
    private $getProductTypeBySku;

    /**
     * @var SetDataToLegacyCatalogInventory
     */
    private $setDataToLegacyCatalogInventory;

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Product
     */
    protected $productFactory;

    /**
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Anymarket\Anymarket\Helper\Data $helper,
        \Magento\Catalog\Model\ProductFactory $productFactory
    ) {
        $this->_objectManager = $objectManager;
        $this->helper = $helper;
        $this->productFactory = $productFactory;

        $this->defaultSourceProvider = interface_exists(\Magento\InventoryCatalogApi\Api\DefaultSourceProviderInterface::class)?ObjectManager::getInstance()->get(\Magento\InventoryCatalogApi\Api\DefaultSourceProviderInterface::class):null;
        $this->isSourceItemsAllowedForProductType = interface_exists(\Magento\InventoryConfigurationApi\Model\IsSourceItemManagementAllowedForProductTypeInterface::class)?ObjectManager::getInstance()->get(\Magento\InventoryConfigurationApi\Model\IsSourceItemManagementAllowedForProductTypeInterface::class):null;
        $this->getProductTypeBySku = interface_exists(\Magento\InventoryCatalogApi\Model\GetProductTypesBySkusInterface::class)?ObjectManager::getInstance()->get(\Magento\InventoryCatalogApi\Model\GetProductTypesBySkusInterface::class):null;
        $this->setDataToLegacyCatalogInventory = class_exists(\Magento\InventoryCatalog\Model\SourceItemsSaveSynchronization\SetDataToLegacyCatalogInventory::class)?ObjectManager::getInstance()->get(\Magento\InventoryCatalog\Model\SourceItemsSaveSynchronization\SetDataToLegacyCatalogInventory::class):null;
        
    }

    /**
     * @param SourceItemsSaveInterface $subject
     * @param void $result
     * @param SourceItemInterface[] $sourceItems
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterExecute(SourceItemsSaveInterface $subject, $result, array $sourceItems): void
    {
        $sourceItemsForSynchronization = [];
        $itemOi = [];
        foreach ($sourceItems as $item) {
            
            $product = $this->productFactory->create();
            $product->load($product->getIdBySku($item->getSku()));
            
            foreach ($product->getStoreIds() as $id => $storeId) {
                $enabled =  $this->helper->getGeneralConfig('anyConfig/general/enable', $storeId);
                $canSyncOrder =  $this->helper->getGeneralConfig('anyConfig/support/create_order_in_anymarket', $storeId);
                if ($enabled == "1" && $canSyncOrder == "0") {
                    
                    $oi =  $this->helper->getGeneralConfig('anyConfig/general/oi', $storeId);
                    $host =  $this->helper->getGeneralConfig('anyConfig/general/host', $storeId);
                    $host = $host . "/public/api/anymarketcallback/stockPrice";

                    if(!isset($itemOi[$oi])){
                        $itemOi[$oi] = [
                            "feed" =>  $this->helper->getGeneralConfig('anyConfig/general/feedStock', $storeId),
                            "host" => $host,
                            "itemId" => $product->getSku()
                        ];
                    }
                }
            }   
        }

        foreach($itemOi as $oi=>$item){
 
            if ($item["feed"] == "1") {
                $this->saveFeed($product->getSku(), "0", $oi);
            } else {
                $this->helper->doCallAnymarket($item["host"], $oi,"" ,$item["itemId"]);
            }
        }
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
}
