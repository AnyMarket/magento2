<?php
namespace Anymarket\Anymarket\Model\ResourceModel;

class Anymarketfeed extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('anymarket_feed', 'entity_id');
    }
}
?>