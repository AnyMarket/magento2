<?php
namespace Anymarket\Anymarket\Block\Adminhtml\Anymarketfeed\Edit;

/**
 * Admin page left menu
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('anymarketfeed_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Anymarketfeed Information'));
    }
}