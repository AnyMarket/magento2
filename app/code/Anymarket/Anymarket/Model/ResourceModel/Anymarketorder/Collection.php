<?php

namespace Anymarket\Anymarket\Model\ResourceModel\Anymarketorder;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Anymarket\Anymarket\Model\Anymarketorder', 'Anymarket\Anymarket\Model\ResourceModel\Anymarketorder');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>