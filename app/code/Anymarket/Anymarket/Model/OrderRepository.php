<?php

declare(strict_types=1);

namespace Anymarket\Anymarket\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Sales\Api\Data\ShippingAssignmentInterface;
use Magento\Sales\Model\Order\ShippingAssignmentBuilder;
use Magento\Sales\Model\ResourceModel\Metadata;
use Magento\Tax\Api\OrderTaxManagementInterface;
use Magento\Payment\Api\Data\PaymentAdditionalInfoInterface;
use Magento\Payment\Api\Data\PaymentAdditionalInfoInterfaceFactory;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\InventoryCatalogApi\Model\GetProductTypesBySkusInterface;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\InventorySalesApi\Api\Data\SalesEventInterface;

/**
 * Repository class
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OrderRepository implements \Anymarket\Anymarket\Api\OrderRepositoryInterface
{
    /**
     * @var Metadata
     */
    protected $metadata;

    /**
     * @var SearchResultFactory
     */
    protected $searchResultFactory = null;

    /**
     * @var OrderExtensionFactory
     */
    private $orderExtensionFactory;

    /**
     * @var ShippingAssignmentBuilder
     */
    private $shippingAssignmentBuilder;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var OrderInterface[]
     */
    protected $registry = [];

    /**
     * @var OrderTaxManagementInterface
     */
    private $orderTaxManagement;

    /**
     * @var PaymentAdditionalInfoFactory
     */
    private $paymentAdditionalInfoFactory;

    /**
     * @var JsonSerializer
     */
    private $serializer;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

     /**
     * @var ReservationBuilderInterface
     */
    private $reservationBuilder;

     /**
     * @var SerializerInterface
     */
    private $serializerInterface;

    /**
     * @var GetSkusByProductIdsInterface
     */
    private $getSkusByProductIds;

    /**
     * @var GetProductTypesBySkusInterface
     */
    private $getProductTypesBySkus;

    /**
     * @var IsSourceItemManagementAllowedForProductTypeInterface
     */
    private $isSourceItemManagementAllowedForProductType;

     /**
     * @var ItemToSellInterfaceFactory
     */
    private $itemsToSellFactory;

    /**
     * @var WebsiteRepositoryInterface
     */
    private $websiteRepository;

    /**
     * @var StockByWebsiteIdResolverInterface
     */
    private $stockByWebsiteIdResolver;

     /**
     * @var CheckItemsQuantity
     */
    private $checkItemsQuantity;

     /**
     * @var SalesEventInterfaceFactory
     */
    private $salesEventFactory;

     /**
     * @var SalesChannelInterfaceFactory
     */
    private $salesChannelFactory;

    /**
     * @var PlaceReservationsForSalesEventInterface
     */
    private $placeReservationsForSalesEvent;

     /**
     * @var SalesEventToArrayConverter
     */
    private $salesEventToArrayConverter;

    /**
     * @var AppendReservationsInterface
     */
    private $appendReservations;

    /**
     * @var GetStockBySalesChannel
     */
    private $getStockBySalesChannel;

    protected $scopeConfig;

    protected $msi;

    /**
     * Constructor
     *
     * @param Metadata $metadata
     * @param SearchResultFactory $searchResultFactory
     * @param CollectionProcessorInterface|null $collectionProcessor
     * @param \Magento\Sales\Api\Data\OrderExtensionFactory|null $orderExtensionFactory
     * @param OrderTaxManagementInterface|null $orderTaxManagement
     * @param PaymentAdditionalInfoInterfaceFactory|null $paymentAdditionalInfoFactory
     * @param JsonSerializer|null $serializer
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     */
    public function __construct(
        Metadata $metadata,
        SearchResultFactory $searchResultFactory,
        WebsiteRepositoryInterface $websiteRepository,
        CollectionProcessorInterface $collectionProcessor = null,
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory = null,
        OrderTaxManagementInterface $orderTaxManagement = null,
        PaymentAdditionalInfoInterfaceFactory $paymentAdditionalInfoFactory = null,
        JsonSerializer $serializer = null,
        SerializerInterface $serializerInterface,
        JoinProcessorInterface $extensionAttributesJoinProcessor = null,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->metadata = $metadata;
        $this->searchResultFactory = $searchResultFactory;
        $this->websiteRepository = $websiteRepository;
        $this->serializerInterface = $serializerInterface;
        $this->collectionProcessor = $collectionProcessor ?: ObjectManager::getInstance()
            ->get(\Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface::class);
        $this->orderExtensionFactory = $orderExtensionFactory ?: ObjectManager::getInstance()
            ->get(\Magento\Sales\Api\Data\OrderExtensionFactory::class);
        $this->orderTaxManagement = $orderTaxManagement ?: ObjectManager::getInstance()
            ->get(OrderTaxManagementInterface::class);
        $this->paymentAdditionalInfoFactory = $paymentAdditionalInfoFactory ?: ObjectManager::getInstance()
            ->get(PaymentAdditionalInfoInterfaceFactory::class);
        $this->serializer = $serializer ?: ObjectManager::getInstance()
            ->get(JsonSerializer::class);
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor
            ?: ObjectManager::getInstance()->get(JoinProcessorInterface::class);

        $scopeResolver = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Framework\App\ScopeResolverInterface::class);

        $storeId = $scopeResolver->getScope()->getId();
        $this->msi = $this->scopeConfig->getValue("anyConfig/general/msi", "store", $storeId) ;

        if($this->msi){
            $this->reservationBuilder = interface_exists(\Magento\InventoryReservationsApi\Model\ReservationBuilderInterface::class)?ObjectManager::getInstance()->get(\Magento\InventoryReservationsApi\Model\ReservationBuilderInterface::class):null;
            $this->getSkusByProductIds = interface_exists(\Magento\InventoryCatalogApi\Model\GetSkusByProductIdsInterface::class)?ObjectManager::getInstance()->get(\Magento\InventoryCatalogApi\Model\GetSkusByProductIdsInterface::class):null;
            $this->getProductTypesBySkus = interface_exists(\Magento\InventoryCatalogApi\Model\GetProductTypesBySkusInterface::class)? ObjectManager::getInstance()->get(\Magento\InventoryCatalogApi\Model\GetProductTypesBySkusInterface::class):null;
            $this->itemsToSellFactory =  class_exists(\Magento\InventorySalesApi\Api\Data\ItemToSellInterfaceFactory::class)?ObjectManager::getInstance()->get(\Magento\InventorySalesApi\Api\Data\ItemToSellInterfaceFactory::class):null;
            $this->salesEventFactory =  class_exists(\Magento\InventorySalesApi\Api\Data\SalesEventInterfaceFactory::class)?ObjectManager::getInstance()->get(\Magento\InventorySalesApi\Api\Data\SalesEventInterfaceFactory::class):null;
            $this->stockByWebsiteIdResolver =  interface_exists(\Magento\InventorySalesApi\Model\StockByWebsiteIdResolverInterface::class)?ObjectManager::getInstance()->get(\Magento\InventorySalesApi\Model\StockByWebsiteIdResolverInterface::class):null;
            $this->checkItemsQuantity =  class_exists(\Magento\InventorySales\Model\CheckItemsQuantity::class)?ObjectManager::getInstance()->get(\Magento\InventorySales\Model\CheckItemsQuantity::class):null;
            $this->salesChannelFactory =  class_exists(\Magento\InventorySalesApi\Api\Data\SalesChannelInterfaceFactory::class)?ObjectManager::getInstance()->get(\Magento\InventorySalesApi\Api\Data\SalesChannelInterfaceFactory::class):null;
            $this->getStockBySalesChannel =  interface_exists(\Magento\InventorySalesApi\Api\GetStockBySalesChannelInterface::class)?ObjectManager::getInstance()->get(\Magento\InventorySalesApi\Api\GetStockBySalesChannelInterface::class):null;
            $this->placeReservationsForSalesEvent =  interface_exists(\Magento\InventorySalesApi\Api\PlaceReservationsForSalesEventInterface::class)?ObjectManager::getInstance()->get(\Magento\InventorySalesApi\Api\PlaceReservationsForSalesEventInterface::class):null;
            $this->salesEventToArrayConverter =  class_exists(\Magento\InventorySales\Model\SalesEventToArrayConverter::class)?ObjectManager::getInstance()->get(\Magento\InventorySales\Model\SalesEventToArrayConverter::class):null;
            $this->isSourceItemManagementAllowedForProductType =  interface_exists(\Magento\InventoryConfigurationApi\Model\IsSourceItemManagementAllowedForProductTypeInterface::class)?ObjectManager::getInstance()->get(\Magento\InventoryConfigurationApi\Model\IsSourceItemManagementAllowedForProductTypeInterface::class):null;
            $this->appendReservations =  interface_exists(\Magento\InventoryReservationsApi\Model\AppendReservationsInterface::class)?ObjectManager::getInstance()->get(\Magento\InventoryReservationsApi\Model\AppendReservationsInterface::class):null;
            
        }else{
            $this->reservationBuilder = null;
            $this->getSkusByProductIds = null;
            $this->getProductTypesBySkus = null;
            $this->itemsToSellFactory = null;
            $this->salesEventFactory =  null;
            $this->stockByWebsiteIdResolver =  null;
            $this->checkItemsQuantity =  null;
            $this->salesChannelFactory =  null;
            $this->getStockBySalesChannel =  null;
            $this->placeReservationsForSalesEvent = null;
            $this->salesEventToArrayConverter =  null;
            $this->isSourceItemManagementAllowedForProductType =  null;
            $this->appendReservations =  null;
        }
    }

   

    /**
     * Set order tax details to extension attributes.
     *
     * @param OrderInterface $order
     * @return void
     */
    private function setOrderTaxDetails(OrderInterface $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();
        $orderTaxDetails = $this->orderTaxManagement->getOrderTaxDetails($order->getEntityId());
        $appliedTaxes = $orderTaxDetails->getAppliedTaxes();

        $extensionAttributes->setAppliedTaxes($appliedTaxes);
        if (!empty($appliedTaxes)) {
            $extensionAttributes->setConvertingFromQuote(true);
        }

        $items = $orderTaxDetails->getItems();
        $extensionAttributes->setItemAppliedTaxes($items);

        $order->setExtensionAttributes($extensionAttributes);
    }

    /**
     * Set additional info to the order.
     *
     * @param OrderInterface $order
     * @return void
     */
    private function setPaymentAdditionalInfo(OrderInterface $order): void
    {
        $extensionAttributes = $order->getExtensionAttributes();
        $paymentAdditionalInformation = $order->getPayment()->getAdditionalInformation();

        $objects = [];
        foreach ($paymentAdditionalInformation as $key => $value) {
            /** @var PaymentAdditionalInfoInterface $additionalInformationObject */
            $additionalInformationObject = $this->paymentAdditionalInfoFactory->create();
            $additionalInformationObject->setKey($key);

            if (!is_string($value)) {
                $value = $this->serializer->serialize($value);
            }
            $additionalInformationObject->setValue($value);

            $objects[] = $additionalInformationObject;
        }
        $extensionAttributes->setPaymentAdditionalInfo($objects);
        $order->setExtensionAttributes($extensionAttributes);
    }

    /**
     * Perform persist operations for one entity
     *
     * @param OrderInterface $entity
     * @return OrderInterface
     * @throws InputException
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(\Magento\Sales\Api\Data\OrderInterface $entity)
    {
        /** @var  \Magento\Sales\Api\Data\OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $entity->getExtensionAttributes();
        $shippingStock = array();
        $stockIds = array();
        if ($entity->getIsNotVirtual() && $extensionAttributes && $extensionAttributes->getShippingAssignments()) {
            $shippingAssignments = $extensionAttributes->getShippingAssignments();
            // $shippingStock = $shippingAssignments;
            foreach($shippingAssignments as $shippingAssign){
                $stockId  = $shippingAssign->getStockId();
                $items = $shippingAssign->getItems();
                foreach($items as $item ){
                    $stockIds[$item->getSku()] = $stockId;
                }
            }
            if (!empty($shippingAssignments)) {
                $shipping = array_shift($shippingAssignments)->getShipping();
                $entity->setShippingAddress($shipping->getAddress());
                $entity->setShippingMethod($shipping->getMethod());
            }
        }

        $this->metadata->getMapper()->save($entity);

        $this->afterPlace($entity, $stockIds);
        $this->registry[$entity->getEntityId()] = $entity;
        return $this->registry[$entity->getEntityId()];
    }

    /**
     * @param OrderInterface $order
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterPlace(OrderInterface $order,  $stockIds = array()): OrderInterface
    {
        $itemsById = $itemsBySku = $itemsToSell = [];
        foreach ($order->getItems() as $item) {
            if (!isset($itemsById[$item->getProductId()])) {
                $itemsById[$item->getProductId()] = 0;
            }
            $itemsById[$item->getProductId()] += $item->getQtyOrdered();
        }
        if($this->getSkusByProductIds){
            $productSkus = $this->getSkusByProductIds->execute(array_keys($itemsById));
        }
        if($this->getProductTypesBySkus){
            $productTypes = $this->getProductTypesBySkus->execute($productSkus);
        }

        if($this->isSourceItemManagementAllowedForProductType){
            foreach ($productSkus as $productId => $sku) {
                if (false === $this->isSourceItemManagementAllowedForProductType->execute($productTypes[$sku])) {
                    continue;
                }

                $itemsBySku[$sku] = (float)$itemsById[$productId];
                $itemsToSell[] = $this->itemsToSellFactory->create([
                    'sku' => $sku,
                    'qty' => -(float)$itemsById[$productId]
                ]);
            }
        }

        $websiteId = (int)$order->getStore()->getWebsiteId();
        $websiteCode = $this->websiteRepository->getById($websiteId)->getCode();
        if($this->stockByWebsiteIdResolver){
            $stockId = (int)$this->stockByWebsiteIdResolver->execute((int)$websiteId)->getStockId();
        }

        if($this->checkItemsQuantity){
            $this->checkItemsQuantity->execute($itemsBySku, $stockId);
        }

        if($this->salesEventFactory){

            /** @var SalesEventInterface $salesEvent */
            $salesEvent = $this->salesEventFactory->create([
                'type' => \Magento\InventorySalesApi\Api\Data\SalesEventInterface::EVENT_ORDER_PLACED,
                'objectType' => \Magento\InventorySalesApi\Api\Data\SalesEventInterface::OBJECT_TYPE_ORDER,
                'objectId' => (string)$order->getEntityId()
            ]);
            $salesChannel = $this->salesChannelFactory->create([
                'data' => [
                    'type' => \Magento\InventorySalesApi\Api\Data\SalesChannelInterface::TYPE_WEBSITE,
                    'code' => $websiteCode
                ]
            ]);
        }

        if(isset($salesChannel) && $salesChannel instanceof \Magento\InventorySalesApi\Api\Data\SalesChannelInterface){
            $this->placeReservationsForSalesEvent($itemsToSell, $salesChannel, $salesEvent, $stockIds);
        }
        return $order;
    }


    /**
     * @inheritdoc
     */
    public function placeReservationsForSalesEvent(array $items, SalesChannelInterface $salesChannel, SalesEventInterface $salesEvent, $stockIds = array()): void
    {
        if (empty($items)) {
            return;
        }

        if($this->getStockBySalesChannel){
            $stockId = $this->getStockBySalesChannel->execute($salesChannel)->getStockId();
        }

        $skus = [];
        /** @var ItemToSellInterface $item */
        foreach ($items as $item) {
            $skus[] = $item->getSku();
        }

        if($this->getProductTypesBySkus){
            $productTypes = $this->getProductTypesBySkus->execute($skus);
        }

        $reservations = [];
        /** @var ItemToSellInterface $item */
        foreach ($items as $item) {
            $currentSku = $item->getSku();
            $skuNotExistInCatalog = !isset($productTypes[$currentSku]);
            if($stockIds[$currentSku]){
                $stockId = $stockIds[$currentSku];
            }
            if($this->isSourceItemManagementAllowedForProductType){

                if ($skuNotExistInCatalog ||
                    $this->isSourceItemManagementAllowedForProductType->execute($productTypes[$currentSku])) {
                    $reservations[] = $this->reservationBuilder
                        ->setSku($item->getSku())
                        ->setQuantity((float)$item->getQuantity())
                        ->setStockId($stockId)
                        ->setMetadata($this->serializerInterface->serialize($this->salesEventToArrayConverter->execute($salesEvent)))
                        ->build();
                }
            }
        }
        if($this->appendReservations){
            $this->appendReservations->execute($reservations);
        }
    }
    /**
     * Set shipping assignments to extension attributes.
     *
     * @param OrderInterface $order
     * @return void
     */
    private function setShippingAssignments(OrderInterface $order)
    {
        /** @var OrderExtensionInterface $extensionAttributes */
        $extensionAttributes = $order->getExtensionAttributes();

        if ($extensionAttributes === null) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        } elseif ($extensionAttributes->getShippingAssignments() !== null) {
            return;
        }
        /** @var ShippingAssignmentInterface $shippingAssignment */
        $shippingAssignments = $this->getShippingAssignmentBuilderDependency();
        $shippingAssignments->setOrderId($order->getEntityId());
        $extensionAttributes->setShippingAssignments($shippingAssignments->create());
        $order->setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get the new ShippingAssignmentBuilder dependency for application code
     *
     * @return ShippingAssignmentBuilder
     * @deprecated 100.0.4
     */
    private function getShippingAssignmentBuilderDependency()
    {
        if (!$this->shippingAssignmentBuilder instanceof ShippingAssignmentBuilder) {
            $this->shippingAssignmentBuilder = \Magento\Framework\App\ObjectManager::getInstance()->get(
                \Magento\Sales\Model\Order\ShippingAssignmentBuilder::class
            );
        }
        return $this->shippingAssignmentBuilder;
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param \Magento\Sales\Api\Data\OrderSearchResultInterface $searchResult
     * @return void
     * @deprecated 101.0.0
     * @throws \Magento\Framework\Exception\InputException
     */
    protected function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \Magento\Sales\Api\Data\OrderSearchResultInterface $searchResult
    ) {
        $fields = [];
        $conditions = [];
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $conditions[] = [$condition => $filter->getValue()];
            $fields[] = $filter->getField();
        }
        if ($fields) {
            $searchResult->addFieldToFilter($fields, $conditions);
        }
    }
}
