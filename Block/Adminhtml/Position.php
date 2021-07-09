<?php
namespace Task\ProductPosition\Block\Adminhtml;

class Position extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_position'; /*block grid.php directory*/
        $this->_blockGroup = 'Task_ProductPosition';
        $this->_headerText = __('Product Position');
        $this->_addButtonLabel = __('Add Record');
        parent::_construct();

    }
}
