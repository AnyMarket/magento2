<?php

namespace Anymarket\Anymarket\Model;

use Magento\Shipping\Model\Config\Source\Allmethods;

class ShippingMethod
{
    /**
     * @var \Magento\Framework\App\ScopeResolverInterface
     */
    private $scopeResolver;

    /**
    * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    * @param Magento\Shipping\Model\Config $shipconfig
    */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Shipping\Model\Config $shipconfig
    ) {
        $this->shipconfig = $shipconfig;
        $this->scopeConfig = $scopeConfig;
    }

    public function getShippingMethods(){
        $storeId = $this->getScopeResolver()->getScope()->getId();

        $activeCarriers = $this->shipconfig->getActiveCarriers($storeId);
        $methods = array();
        foreach($activeCarriers as $carrierCode => $carrierModel) {
          $carrierTitle = $this->scopeConfig->getValue('carriers/'.$carrierCode.'/title');
          $methods[] = array('value'=>$carrierCode,'label'=>$carrierTitle);
        }
        return $methods;        
    }

    private function getScopeResolver()
    {
        if ($this->scopeResolver == null) {
            $this->scopeResolver = \Magento\Framework\App\ObjectManager::getInstance()
                ->get(\Magento\Framework\App\ScopeResolverInterface::class);
        }

        return $this->scopeResolver;
    }


}