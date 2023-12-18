<?php

namespace Anymarket\Anymarket\Model;

class QuoteManagement
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Quote\Model\QuoteFactory
     */
    protected $quoteFactory;

    /**
     * @var \Magento\Quote\Api\CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
    * @param Magento\Framework\App\Helper\Context $context
    * @param Magento\Store\Model\StoreManagerInterface $storeManager
    * @param Magento\Quote\Model\QuoteFactory $quoteFactory,
    */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        $this->_storeManager = $storeManager;
        $this->quoteFactory = $quoteFactory;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function putItemsPrice($idCart, $quote)
    {
        $quoteMg = $this->quoteFactory->create()->load($idCart);
        $items = $quote->getItems();

        foreach ($quoteMg->getAllItems() as $itemQ) {
            $sku = $itemQ->getData('sku');

            foreach ($items as $prdJson) {
                $data = $prdJson->getData();
                if($data['sku'] == $sku){
                    $itemId = $itemQ->getData('item_id');

                    $item = $quoteMg->getItemById($itemId);
                    $item->setCustomPrice($data['price']);
                    $item->setPrice($data['price']);
                    $item->setOriginalCustomPrice($data['price']);
                    $item->getProduct()->setIsSuperMode(true);
                    $item->save();
                    break;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function putShippingAmount($idCart, $shipAmount)
    {
        $quote = $this->quoteRepository->getActive($idCart);

        $quote->getShippingAddress()->setShippingAmount($shipAmount);
        $quote->getShippingAddress()->setBaseShippingAmount($shipAmount);

        $actBaseSubTotal = $quote->getShippingAddress()->getBaseSubtotal();
        $newGrandTotal = $actBaseSubTotal + $shipAmount;
        $quote->getShippingAddress()->setGrandTotal($newGrandTotal);
        $quote->getShippingAddress()->setBaseGrandTotal($newGrandTotal);

        $quote->setGrandTotal($newGrandTotal);
        $quote->setBaseGrandTotal($newGrandTotal);

        $quote->getShippingAddress()->collectShippingRates();
        $quote->save();
    }

    /**
     * {@inheritdoc}
     */
    public function putInterestValue($idCart, $interestValue)
    {
        $quote = $this->quoteRepository->getActive($idCart);

        $quote->getShippingAddress()->setTaxAmount($interestValue);
        $quote->getShippingAddress()->setBaseTaxAmount($interestValue);

        $actBaseSubTotal = $quote->getShippingAddress()->getBaseSubtotal();
        $newGrandTotal = $actBaseSubTotal + $interestValue;

        $quote->setGrandTotal($newGrandTotal);
        $quote->setBaseGrandTotal($newGrandTotal);
        $quote->save();
    }

}