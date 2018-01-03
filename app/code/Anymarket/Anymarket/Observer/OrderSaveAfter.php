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
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager
    )
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * customer register event handler
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $helper = $this->_objectManager->create('Anymarket\Anymarket\Helper\Data');

        $enabled = $helper->getGeneralConfig('anyConfig/general/enable');
        $canCreateOrder = $helper->getGeneralConfig('anyConfig/general/create_order_in_anymarket');
        if ($enabled == "1" && $canCreateOrder == "1") {
            $orderIds = $observer->getEvent()->getOrderIds();
            if (count($orderIds) > 0) {
                $orderId = $orderIds[0];

                $oi = $helper->getGeneralConfig('anyConfig/general/oi');
                $host = $helper->getGeneralConfig('anyConfig/general/host');

                $host = $host . "/public/api/anymarketcallback/order/" . $oi . "/MAGENTO_2/" . ScopeInterface::SCOPE_STORE . "/" . $orderId;

                $helper->doCallAnymarket($host);
            }
        }
    }
}
