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
use Magento\InventoryReservationsApi\Model\ReservationBuilderInterface;
use Magento\InventoryReservationsApi\Model\ReservationInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\InventoryCatalogApi\Model\GetSkusByProductIdsInterface;
use Magento\InventoryCatalogApi\Model\GetProductTypesBySkusInterface;
use Magento\InventoryConfigurationApi\Model\IsSourceItemManagementAllowedForProductTypeInterface;
use Magento\InventorySalesApi\Api\Data\ItemToSellInterfaceFactory;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Magento\InventorySalesApi\Model\StockByWebsiteIdResolverInterface;
use Magento\InventorySales\Model\CheckItemsQuantity;
use Magento\InventorySalesApi\Api\Data\SalesEventInterface;
use Magento\InventorySalesApi\Api\Data\SalesEventInterfaceFactory;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterface;
use Magento\InventorySalesApi\Api\Data\SalesChannelInterfaceFactory;
use Magento\InventorySalesApi\Api\PlaceReservationsForSalesEventInterface;
use Magento\InventorySalesApi\Api\GetStockBySalesChannelInterface;
use Magento\InventorySales\Model\SalesEventToArrayConverter;
use Magento\InventoryReservationsApi\Model\AppendReservationsInterface;

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
        ReservationBuilderInterface $reservationBuilder,
        GetSkusByProductIdsInterface $getSkusByProductIds,
        GetProductTypesBySkusInterface $getProductTypesBySkus,
        ItemToSellInterfaceFactory $itemsToSellFactory,
        WebsiteRepositoryInterface $websiteRepository,
        SalesEventInterfaceFactory $salesEventFactory,
        StockByWebsiteIdResolverInterface $stockByWebsiteIdResolver,
        CheckItemsQuantity $checkItemsQuantity,
        SalesChannelInterfaceFactory $salesChannelFactory,
        GetStockBySalesChannelInterface $getStockBySalesChannel,
        PlaceReservationsForSalesEventInterface $placeReservationsForSalesEvent,
        SalesEventToArrayConverter $salesEventToArrayConverter,
        IsSourceItemManagementAllowedForProductTypeInterface $isSourceItemManagementAllowedForProductType,
        CollectionProcessorInterface $collectionProcessor = null,
        AppendReservationsInterface $appendReservations,
        \Magento\Sales\Api\Data\OrderExtensionFactory $orderExtensionFactory = null,
        OrderTaxManagementInterface $orderTaxManagement = null,
        PaymentAdditionalInfoInterfaceFactory $paymentAdditionalInfoFactory = null,
        JsonSerializer $serializer = null,
        SerializerInterface $serializerInterface,
        JoinProcessorInterface $extensionAttributesJoinProcessor = null
    ) {
        $this->metadata = $metadata;
        $this->searchResultFactory = $searchResultFactory;
        $this->reservationBuilder = $reservationBuilder;
        $this->getSkusByProductIds = $getSkusByProductIds;
        $this->getProductTypesBySkus = $getProductTypesBySkus;
        $this->itemsToSellFactory = $itemsToSellFactory;
        $this->websiteRepository = $websiteRepository;
        $this->checkItemsQuantity = $checkItemsQuantity;
        $this->salesEventFactory = $salesEventFactory;
        $this->salesChannelFactory = $salesChannelFactory;
        $this->stockByWebsiteIdResolver = $stockByWebsiteIdResolver;
        $this->getStockBySalesChannel = $getStockBySalesChannel;
        $this->placeReservationsForSalesEvent = $placeReservationsForSalesEvent;
        $this->isSourceItemManagementAllowedForProductType = $isSourceItemManagementAllowedForProductType;
        $this->serializerInterface = $serializerInterface;
        $this->appendReservations = $appendReservations;
        $this->salesEventToArrayConverter = $salesEventToArrayConverter;
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

        // foreach($shippingStock as $shippingAssign){
        //     $stockId  = $shippingAssign->getStockId();
        //     $items = $shippingAssign->getItems();
        //     foreach($items as $item ){
        //             $this->reservationBuilder
        //                 ->setSku((string)$item->getSku())
        //                 ->setQuantity((float)$item->getQtyOrdered())
        //                 ->setStockId((int)$stockId)
        //                 ->setMetadata(
        //                     $this->serializerInterface->serialize(
        //                         [
        //                             'event_type' => 'order_placed',
        //                             'object_type' => 'order',
        //                             'object_id' => $entity->getEntityId(),
        //                         ]
        //                     )
        //                 )
        //                 ->build();
        //     }
        // }
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
        $productSkus = $this->getSkusByProductIds->execute(array_keys($itemsById));
        $productTypes = $this->getProductTypesBySkus->execute($productSkus);

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

        $websiteId = (int)$order->getStore()->getWebsiteId();
        $websiteCode = $this->websiteRepository->getById($websiteId)->getCode();
        $stockId = (int)$this->stockByWebsiteIdResolver->execute((int)$websiteId)->getStockId();

        $this->checkItemsQuantity->execute($itemsBySku, $stockId);

        /** @var SalesEventInterface $salesEvent */
        $salesEvent = $this->salesEventFactory->create([
            'type' => SalesEventInterface::EVENT_ORDER_PLACED,
            'objectType' => SalesEventInterface::OBJECT_TYPE_ORDER,
            'objectId' => (string)$order->getEntityId()
        ]);
        $salesChannel = $this->salesChannelFactory->create([
            'data' => [
                'type' => SalesChannelInterface::TYPE_WEBSITE,
                'code' => $websiteCode
            ]
        ]);

        $this->placeReservationsForSalesEvent($itemsToSell, $salesChannel, $salesEvent, $stockIds);
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

        $stockId = $this->getStockBySalesChannel->execute($salesChannel)->getStockId();

        $skus = [];
        /** @var ItemToSellInterface $item */
        foreach ($items as $item) {
            $skus[] = $item->getSku();
        }
        $productTypes = $this->getProductTypesBySkus->execute($skus);

        $reservations = [];
        /** @var ItemToSellInterface $item */
        foreach ($items as $item) {
            $currentSku = $item->getSku();
            $skuNotExistInCatalog = !isset($productTypes[$currentSku]);
            if($stockIds[$currentSku]){
                $stockId = $stockIds[$currentSku];
            }
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
        $this->appendReservations->execute($reservations);
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
