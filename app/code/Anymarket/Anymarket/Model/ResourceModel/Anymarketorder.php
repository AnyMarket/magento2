<?php
namespace Anymarket\Anymarket\Model\ResourceModel;

class Anymarketorder extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('anymarket_order', 'entity_id');
    }
}
?>