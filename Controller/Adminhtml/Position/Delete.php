<?php
namespace Task\ProductPosition\Controller\Adminhtml\Position;

use Magento\Backend\App\Action\Context;
use Task\ProductPosition\Model\Product;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */

    public function __construct(
        Context $context,
        Product $product
    ) {
        $this->product = $product;
        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->getRequest()->getParam('id')) {
            try {
                $this->product->load($this->getRequest()->getParam('id'));
                $this->product->delete();
                $this->messageManager->addSuccessMessage(__('The item has been deleted.'));
                return $this->_redirect('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__($e->getMessage()));
                return $this->_redirect('*/*/', ['id' => $this->getRequest()->getParam('id')]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a item to delete.'));
        return $this->_redirect('*/*/', ['id' => $this->getRequest()->getParam('id')]);
    }
}
