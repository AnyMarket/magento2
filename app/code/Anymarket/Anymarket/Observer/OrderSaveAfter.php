<?php

namespace Anymarket\Anymarket\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;

class OrderSaveAfter implements ObserverInterface
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
        $order = $observer->getEvent()->getOrder();
        $storeId = $order->getStoreId();

        $helper = $this->_objectManager->create('Anymarket\Anymarket\Helper\Data');

        foreach ($this->storeManager->getStores() as $storeId => $storeData) {
            $enabled = $helper->getGeneralConfig('anyConfig/general/enable', $storeId);
            if ($enabled == "1") {
                if ($order instanceof \Magento\Framework\Model\AbstractModel) {
                    $canCreateOrder = $helper->getGeneralConfig('anyConfig/support/create_order_in_anymarket', $storeId);
                    if ($this->isNewOrder($order) && $canCreateOrder == "0") {
                        return $this;
                    }

                    $orderId = $order->getId();
                    $oi = $helper->getGeneralConfig('anyConfig/general/oi', $storeId);
                    if ($feed = $helper->getGeneralConfig('anyConfig/general/feedProduct', $storeId) == "1") {
                        $this->saveFeed($orderId, "1", $oi);
                    } else {
                        $host = $helper->getGeneralConfig('anyConfig/general/host', $storeId);
                        $host = $host . "/public/api/anymarketcallback/order";
                        $helper->doCallAnymarket($host, $oi, ScopeInterface::SCOPE_STORE, $orderId);
                    }
                }
            }
        }
        return $this;
    }

    private function isNewOrder($order)
    {
        return $order->getData('created_at') == $order->getData('updated_at');
    }

}
