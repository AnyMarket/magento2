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
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    ) {
        $this->_objectManager = $objectManager;

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

        
        foreach ($sourceItems as $item) {

            $storeId = $item->getStoreId();
            if(!$storeId){
                $storeId = 1;
            }

            $helper = $this->_objectManager->create('Anymarket\Anymarket\Helper\Data');

            $enabled = $helper->getGeneralConfig('anyConfig/general/enable', $storeId);
            $canSyncOrder = $helper->getGeneralConfig('anyConfig/support/create_order_in_anymarket', $storeId);
            if ($enabled == "1" && $canSyncOrder == "0") {
                $productId = $this->_objectManager->get('Magento\Catalog\Model\Product')->getIdBySku($item->getSku());

                $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($productId);

                $oi = $helper->getGeneralConfig('anyConfig/general/oi', $storeId);
                if ($feed = $helper->getGeneralConfig('anyConfig/general/feedStock', $storeId) == "1") {
                    $this->saveFeed($product->getSku(), "0", $oi);
                } else {
                    $host = $helper->getGeneralConfig('anyConfig/general/host', $storeId);
                    $host = $host . "/public/api/anymarketcallback/stockPrice";
                    $helper->doCallAnymarket($host, $oi, "", $product->getSku());
                }
            }
        }
    }

    private function saveFeed($id, $type, $oi)
    {
        $modelFeed = $this->_objectManager->create('Anymarket\Anymarket\Model\Anymarketfeed');
        $modelFeed->setIdItem($id);
        $modelFeed->setType($type);
        $modelFeed->setOi($oi);
        $modelFeed->save();
    }
}
