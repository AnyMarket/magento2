<?php
namespace Anymarket\Anymarket\Block\Adminhtml\Anymarketorder\Edit;

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
        $this->setId('anymarketorder_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Anymarketorder Information'));
    }
}