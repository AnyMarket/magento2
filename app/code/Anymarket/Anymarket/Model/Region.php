<?php

namespace Anymarket\Anymarket\Model;

use Magento\Shipping\Model\Config\Source\Allmethods;

class Region
{
    /**
     * @var \Magento\Framework\App\ScopeResolverInterface
     */
    private $scopeResolver;

    /**
    * @param Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    * @param \Magento\Directory\Model\RegionFactory $regionFactory
    */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Directory\Model\RegionFactory $regionFactory
    ) {
        $this->regionFactory = $regionFactory;
        $this->scopeConfig = $scopeConfig;
    }

    public function getRegions($countryCode){
        $regions = $this->regionFactory->create()->getCollection()->addFieldToFilter('country_id', $countryCode);
        return $regions->getData();
    }

}