<?php

namespace Anymarket\Anymarket\Model;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Payment\Model\Config;

class PaymentMethod
{
    /**
    * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    * @param Magento\Payment\Model\Config $payConfig
    */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Payment\Model\Config $payConfig
    ) {
        $this->paymentConfig = $payConfig;
        $this->scopeConfig = $scopeConfig;
    }

    public function getPaymentMethods(){
        $activePayments = $this->paymentConfig->getActiveMethods();

        $methods = array();
        foreach($activePayments as $paymentCode => $paymentModel) {
          $paymentTitle = $this->scopeConfig->getValue('payment/'.$paymentCode.'/title');
          $methods[] = array('value'=>$paymentCode,'label'=>$paymentTitle);
        }
        return $methods;        
    }

}