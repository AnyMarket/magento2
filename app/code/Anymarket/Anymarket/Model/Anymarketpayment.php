<?php
namespace Anymarket\Anymarket\Model;

class Anymarketpayment extends \Magento\Payment\Model\Method\AbstractMethod
{
    const PAYMENT_METHOD_ANYMARKET_CODE = 'anymarket_payment';

    protected $_code = self::PAYMENT_METHOD_ANYMARKET_CODE;
    protected $_isOffline = true;
    protected $_canCancelInvoice = true;
    protected $_canOrder = true;
    protected $_canRefund = true;

    public function isAvailable(
        \Magento\Quote\Api\Data\CartInterface $quote = null
    ) {
        $data = json_decode(file_get_contents('php://input'), true);
        if(!isset($data['paymentMethod']) || !isset($data['paymentMethod']['additional_data']) || !$this->isAnymarketQuote($data['paymentMethod']['additional_data'])) {
            return false;
        }
        return parent::isAvailable($quote);
    }

    private function isAnymarketQuote($addData) {
        foreach ($addData as $attr) {
            if($attr == 'anymarketMethod'){
                return true;
            }
        }
        return false;
    }

}
