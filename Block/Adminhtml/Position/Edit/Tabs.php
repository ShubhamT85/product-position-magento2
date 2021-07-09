<?php
namespace Task\ProductPosition\Block\Adminhtml\Position\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    protected function _construct()
    {

        parent::_construct();
        $this->setId('checkmodule_position_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Product Position Information'));
    }
}
