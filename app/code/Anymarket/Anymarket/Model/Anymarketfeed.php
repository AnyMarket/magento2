<?php
namespace Anymarket\Anymarket\Model;

class Anymarketfeed extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Anymarket\Anymarket\Model\ResourceModel\Anymarketfeed');
    }
}
?>