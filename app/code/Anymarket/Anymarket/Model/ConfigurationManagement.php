<?php

namespace Anymarket\Anymarket\Model;

class ConfigurationManagement
{
    /**
    * @param Magento\Framework\App\Helper\Context $context
    * @param Magento\Store\Model\StoreManagerInterface $storeManager
    */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\Storage\WriterInterface $configManager
    ) {
        $this->_sconfigManager = $configManager;
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

}