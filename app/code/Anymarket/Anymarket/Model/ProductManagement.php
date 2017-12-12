<?php

namespace Anymarket\Anymarket\Model;

class ProductManagement
{
    /**
     * @param Magento\Framework\App\Helper\Context $context
     * @param Magento\ConfigurableProduct\Model\Product\Type\Configurable $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configprodManager
    )
    {
        $this->_configprodManager = $configprodManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurableBySimple($idProduct)
    {
        $parentIds = $this->_configprodManager->getParentIdsByChild($idProduct);
        if ($parentIds == null) {
            return "";
        }
        return array_shift($parentIds);
    }

}
