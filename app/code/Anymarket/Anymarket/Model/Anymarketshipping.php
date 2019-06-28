<?php
namespace Anymarket\Anymarket\Model;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Rate\Result;

class Anymarketshipping extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    protected $_code = 'anymarket_shipping';
    protected $_isFixed = true;
    protected $_rateResultFactory;
    protected $_rateMethodFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function collectRates(RateRequest $request)
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if(!isset($data['addressInformation']) || !isset($data['addressInformation']['custom_attributes'])) {
            return false;
        }
        $customAttrs = $data['addressInformation']['custom_attributes'];

        $shippingPrice = $this->getSpecificCustomAttr($customAttrs, 'anymarketFreight');
        $shippingCarrier = $this->getSpecificCustomAttr($customAttrs, 'anymarketCarrier');
        if ($shippingPrice == null || $shippingCarrier == null) {
            return $this;
        }

        $result = $this->_rateResultFactory->create();
        $methodCode = $data['addressInformation']['shipping_method_code'];

        $method = $this->_rateMethodFactory->create();
        $method->setCarrier($this->_code);
        $method->setCarrierTitle("AnyMarket (".$shippingCarrier.")");
        $method->setMethod($methodCode);
        $method->setMethodTitle($methodCode);

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);

        $result->append($method);
        return $result;
    }

    private function getSpecificCustomAttr($customAttrs, $attrCode) {
        $valueAttr = null;
        foreach ($customAttrs as $attr) {
            if($attr['attribute_code'] == $attrCode){
                $valueAttr = $attr['value'];
                break;
            }
        }
        return $valueAttr;
    }

    /**
     * getAllowedMethods
     *
     * @param array
     */
    public function getAllowedMethods()
    {
        return ['anymarket' => $this->getConfigData('name')];
    }
}
