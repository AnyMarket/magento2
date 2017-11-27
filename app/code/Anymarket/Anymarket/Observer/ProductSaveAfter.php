<?php
namespace Anymarket\Anymarket\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\ScopeInterface;

class ProductSaveAfter implements ObserverInterface
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
    ) {
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
        if($enabled == "1"){
            $product = $observer->getEvent()->getProduct();

            $oi = $helper->getGeneralConfig('anyConfig/general/oi');
            $host = $helper->getGeneralConfig('anyConfig/general/host');

            $host = $host."/public/api/anymarketcallback/product/".$oi."/MAGENTO_2/".ScopeInterface::SCOPE_STORE."/".$product->getSku();

            $helper->doCallAnymarket($host);
        }
    }
}
