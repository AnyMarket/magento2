<?php

namespace Anymarket\Anymarket\Api\Data;

/**
 * Interface AnymarketConfigurationInterface
 * @api
 */
interface AnymarketConfigurationInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const KEY_SEND_ORDER = 'sendOrder';

    const KEY_SEND_PRODUCT = 'sendProduct';

    const KEY_ATTR_INTEGRATION_ANY = 'attrIntegrationAny';

    /**
     * Returns send_order
     *
     * @return string
     */
    public function getSendOrder();

    /**
     * Sets send_order
     *
     * @param string $canSendOrder
     * @return $this
     */
    public function setSendOrder(String $canSendOrder);

    /**
     * Returns send_product
     *
     * @return string
     */
    public function getSendProduct();

    /**
     * Sets send_product
     *
     * @param string $canSendProduct
     * @return $this
     */
    public function setSendProduct(String $canSendProduct);

    /**
     * Returns attr_integration_any
     *
     * @return string
     */
    public function getAttrIntegrationAny();

    /**
     * Sets attr_integ_any
     *
     * @param string $attrIntegrationAny
     * @return $this
     */
    public function setAttrIntegrationAny(String $attrIntegrationAny);


}