<?php

namespace Anymarket\Anymarket\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class UpgradeData implements UpgradeDataInterface
{

    protected $configWriter;

	public function __construct(WriterInterface $configWriter)
	{
		$this->configWriter = $configWriter;
	}

	public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
		if (version_compare($context->getVersion(), '1.1.0', '<')) {

            if(!class_exists(\Magento\InventorySalesApi\Api\Data\ItemToSellInterfaceFactory::class)
            || !class_exists(\Magento\InventorySalesApi\Api\Data\SalesEventInterfaceFactory::class)
            || !class_exists(\Magento\InventorySales\Model\CheckItemsQuantity::class)
            || !class_exists(\Magento\InventorySalesApi\Api\Data\SalesChannelInterfaceFactory::class)
            || !class_exists(\Magento\InventorySales\Model\SalesEventToArrayConverter::class)
            ){
                $this->configWriter->save("anyConfig/general/msi", 0, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);
            }else{
                $this->configWriter->save("anyConfig/general/msi", 1, $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT, $scopeId = 0);

            }
		}
	}
}