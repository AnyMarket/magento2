<?php 
namespace Anymarket\Anymarket\Plugin\Api; 
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\Data\OrderItemSearchResultInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\InventoryApi\Api\Data\SourceItemInterface;

class OrderItemRepository {


    const SOURCE = 'source';

    /**
     * Order Extension Attributes Factory
     *
     * @var OrderExtensionFactory
     */
    protected $extensionFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

     /**
     * @var SourceItemRepositoryInterface
     */
    private $sourceItemRepository;

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    protected $sourceRepository;

    /**
     * @var GetSourcesAssignedToStockOrderedByPriority
     */
    private $getSourcesAssignedToStockOrderedByPriority;

    protected $_stockItemRepository;

    protected $msi;

    protected $scopeConfig;

    /**
     * OrderRepositoryPlugin constructor
     *
     * @param OrderExtensionFactory $extensionFactory
     */
    public function __construct(
        \Magento\Sales\Api\Data\OrderItemExtensionInterfaceFactory $extensionFactory, 
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\CatalogInventory\Model\Stock\StockItemRepository $stockItemRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->extensionFactory = $extensionFactory;
        $this->_objectManager = $objectManager;
        $this->_stockItemRepository = $stockItemRepository;

        $this->searchCriteriaBuilder = class_exists(\Magento\Framework\Api\SearchCriteriaBuilder::class)?ObjectManager::getInstance()->get(\Magento\Framework\Api\SearchCriteriaBuilder::class):null;
        $this->sourceItemRepository = class_exists(\Magento\Inventory\Model\SourceItemRepository::class)?ObjectManager::getInstance()->get(\Magento\Inventory\Model\SourceItemRepository::class):null;
        $this->sourceRepository = class_exists(\Magento\InventoryApi\Api\SourceRepositoryInterface::class)?ObjectManager::getInstance()->get(\Magento\InventoryApi\Api\SourceRepositoryInterface::class):null;
        $this->getSourcesAssignedToStockOrderedByPriority = class_exists(\Magento\Inventory\Model\Source\Command\GetSourcesAssignedToStockOrderedByPriority::class)?ObjectManager::getInstance()->get(\Magento\Inventory\Model\Source\Command\GetSourcesAssignedToStockOrderedByPriority::class):null;

        $scopeResolver = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Framework\App\ScopeResolverInterface::class);
        $storeId = $scopeResolver->getScope()->getId();
        $this->msi = $this->scopeConfig->getValue("anyConfig/general/msi", "store", $storeId) ;

    }

    /**
     * Add "delivery_type" extension attribute to order data object to make it accessible in API data
     *
     * @param OrderItemRepositoryInterface $subject
     * @param OrderInterface $order
     *
     * @return OrderInterface
     */
    public function afterGet(OrderItemRepositoryInterface $subject, OrderItemInterface $item)
    {
        if($this->msi){
            $source = $item->getData(self::SOURCE);
            $extensionAttributes = $item->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            $extensionAttributes->setSource($source);
            $item->setExtensionAttributes($extensionAttributes);

            return $item;
        }
    }

    /**
     * Add "delivery_type" extension attribute to order data object to make it accessible in API data
     *
     * @param OrderItemRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchResult
     *
     * @return OrderSearchResultInterface
     */
    public function afterGetList(OrderItemRepositoryInterface $subject, OrderItemSearchResultInterface $searchResult)
    {
        if($this->msi){
            $items = $searchResult->getItems();

            foreach ($items as &$item) {
                $store_id = $item->getData("store_id");
                $stock = $this->_stockItemRepository->get($item->getData("product_id"));
                $stock_id =$stock->getStockId();
                if($stock_id){
                    foreach ($this->getSourcesAssignedToStockOrderedByPriority->execute($stock_id) as $itemSource) {
                        $sources = $itemSource->getData();
                    }    
                }
                $source = 0;
                if($this->searchCriteriaBuilder){
                    $searchCriteria = $this->searchCriteriaBuilder
                    ->addFilter(SourceItemInterface::SKU, $item->getSku())
                    ->create();    

                    if($this->sourceItemRepository){
                        $result = $this->sourceItemRepository->getList($searchCriteria)->getItems();
                        if($result){
                            foreach($result as $sourceData){
                                $source = $sourceData->getSourceCode();
                                $sourceData = $this->sourceRepository->get($source);
                            }
                        }
                    }
                }
                
                $options = $item->getProductOption()->getExtensionAttributes()->getCustomOptions();
                foreach($options as $op){
                    $optionValue = $op->getOptionValue();
                    $optionId = $op->getOptionId();
                }
                if($optionId){
                    $source = $optionId;
                }else if(!$source){
                    $source = "default";
                }

                $extensionAttributes = $item->getExtensionAttributes();
                $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
                $extensionAttributes->setSource($source);
                $item->setExtensionAttributes($extensionAttributes);
            }
        } 
        return $searchResult;
    }
}