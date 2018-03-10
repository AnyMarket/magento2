<?php

namespace Anymarket\Anymarket\Model;

use Magento\Shipping\Model\Config\Source\Allmethods;

class ShippingMethod
{
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
        $activeCarriers = $this->shipconfig->getActiveCarriers();
        $methods = array();
        foreach($activeCarriers as $carrierCode => $carrierModel) {
          $carrierTitle = $this->scopeConfig->getValue('carriers/'.$carrierCode.'/title');
          $methods[] = array('value'=>$carrierCode,'label'=>$carrierTitle);
        }
        return $methods;        
    }

}