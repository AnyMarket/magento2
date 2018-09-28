<?php

namespace Anymarket\Anymarket\Model;

class FeedManagement
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
    public function getFeedByType($typeFeed)
    {
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $collectionFeed = $_objectManager->create('Anymarket\Anymarket\Model\Anymarketfeed')->getCollection();
        $collectionFeed->addFieldToFilter('type', $typeFeed);

        return $collectionFeed->getData();
    }


}