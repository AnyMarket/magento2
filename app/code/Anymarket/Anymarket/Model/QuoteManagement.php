<?php

namespace Anymarket\Anymarket\Model;

class QuoteManagement
{
    /**
    * @param Magento\Framework\App\Helper\Context $context
    * @param Magento\Store\Model\StoreManagerInterface $storeManager
    * @param Magento\Quote\Model\QuoteFactory $quoteFactory,
    */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Quote\Model\QuoteFactory $quoteFactory
    ) {
        $this->_storeManager = $storeManager;
        $this->quoteFactory = $quoteFactory;
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
                    $item->setQty((double) $data['qty']);
                    $item->setCustomPrice($data['price']);
                    $item->setOriginalCustomPrice($data['price']);
                    $item->getProduct()->setIsSuperMode(true);
                    $item->save();
                    break;
                }
            }

        }

    }

}
