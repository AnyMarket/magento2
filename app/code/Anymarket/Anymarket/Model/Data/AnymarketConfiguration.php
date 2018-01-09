<?php

namespace Anymarket\Anymarket\Model\Data;

class AnymarketConfiguration implements \Anymarket\Anymarket\Api\Data\AnymarketConfigurationInterface
{

    /**
     * SendOrder
     *
     * item for anymarket method
     *
     * @var string
     */
    protected $_sendOrder = "0";

    /**
     * SendProduct
     *
     * item for anymarket method
     *
     * @var string
     */
    protected $_sendProduct = "0";

    /**
     * AttrIntegrationAny
     *
     * item for anymarket method
     *
     * @var string
     */
    protected $_attrIntegrationAny = "";

    public function __construct(\Magento\Framework\App\Helper\Context $context)
    {
    }

    /**
     * Returns send_order
     *
     * @return string
     */
    public function getSendOrder()
    {
        return $this->_sendOrder;
    }

    /**
     * Sets send_order
     *
     * @param string $canSendOrder
     * @return $this
     */
    public function setSendOrder(String $canSendOrder)
    {
        $this->_sendOrder = $canSendOrder;
        return $this;
    }

    /**
     * Returns send_product
     *
     * @return string
     */
    public function getSendProduct()
    {
        return $this->_sendProduct;
    }

    /**
     * Sets send_product
     *
     * @param string $canSendProduct
     * @return $this
     */
    public function setSendProduct(String $canSendProduct)
    {
        $this->_sendProduct = $canSendProduct;
        return $this;
    }

    /**
     * Returns attr_integration_any
     *
     * @return string
     */
    public function getAttrIntegrationAny()
    {
        return $this->_attrIntegrationAny;
    }

    /**
     * Sets attr_integ_any
     *
     * @param string $attrIntegrationAny
     * @return $this
     */
    public function setAttrIntegrationAny(String $attrIntegrationAny)
    {
        $this->_attrIntegrationAny = $attrIntegrationAny;
        return $this;
    }
}
