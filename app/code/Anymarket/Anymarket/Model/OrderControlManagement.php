<?php

namespace Anymarket\Anymarket\Model;

class OrderControlManagement
{
    /**
     * @param Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    {
    }

    /**
     * {@inheritdoc}
     */
    public function postOrderControl($order)
    {
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $modelOrder = $_objectManager->create('Anymarket\Anymarket\Model\Anymarketorder');
        $modelOrder->setIdAnymarket($order->getIdAnymarket());
        $modelOrder->setIdMagento($order->getIdMagento());
        $modelOrder->setMarketplace($order->getMarketplace());
        $modelOrder->setOi($order->getOi());
        $modelOrder->save();
        return "";
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderByIdAnymarket($idAnymarket)
    {
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collectionOrder = $_objectManager->create('Anymarket\Anymarket\Model\Anymarketorder')->getCollection();
        $collectionOrder->addFieldToFilter('id_anymarket', $idAnymarket);

        return $collectionOrder->getData();
    }


}