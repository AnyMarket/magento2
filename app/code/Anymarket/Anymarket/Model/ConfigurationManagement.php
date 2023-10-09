<?php

namespace Anymarket\Anymarket\Model;

class ConfigurationManagement
{

    const VERSION = "3.3.0";

    protected $msi = true;

    protected $_sconfigManager;

    protected $scopeConfig;


    /**
    * @param Magento\Framework\App\Helper\Context $context
    * @param Magento\Store\Model\StoreManagerInterface $storeManager
    */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\Storage\WriterInterface $configManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_sconfigManager = $configManager;
        $this->scopeConfig = $scopeConfig;
        $scopeResolver = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Framework\App\ScopeResolverInterface::class);
        $storeId = $scopeResolver->getScope()->getId();
        $this->msi = $this->scopeConfig->getValue("anyConfig/general/msi", "store", $storeId) ;
    }

    /**
     * {@inheritdoc}
     */
    public function putConfiguration($configuration)
    {
        $sendOrder = $configuration->getSendOrder();
        $sendProduct = $configuration->getSendProduct();
        $attrIntegAny = $configuration->getAttrIntegrationAny();

        $this->_sconfigManager->save('anyConfig/support/create_order_in_anymarket', $sendOrder);
        $this->_sconfigManager->save('anyConfig/support/create_product_in_anymarket', $sendProduct);
        $this->_sconfigManager->save('anyConfig/support/attr_integration_anymarket', $attrIntegAny);
        return "";
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        $data = (array("version"=> self::VERSION, "msi"=> $this->msi));
        
        return $data;
    }

}