<?php

namespace Anymarket\Anymarket\Model\ResourceModel\Anymarketfeed;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Anymarket\Anymarket\Model\Anymarketfeed', 'Anymarket\Anymarket\Model\ResourceModel\Anymarketfeed');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>