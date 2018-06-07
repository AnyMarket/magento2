<?php

namespace Anymarket\Anymarket\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Payment\Model\Config;

class PaymentMethod
{
    /**
     * @var \Magento\Framework\App\ScopeResolverInterface
     */
    private $scopeResolver;

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
        $storeId = $this->getScopeResolver()->getScope()->getId();

        $methods = array();
        foreach ($this->scopeConfig->getValue("payment", "store", $storeId) as $code => $data) {
            if (isset($data['active'], $data['model']) && (bool)$data['active']) {
                $paymentTitle = $this->scopeConfig->getValue('payment/'.$code.'/title');
                $methods[] = array('value'=>$code,'label'=>$paymentTitle);
            }
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