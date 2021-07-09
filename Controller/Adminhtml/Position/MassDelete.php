<?php
namespace Task\ProductPosition\Controller\Adminhtml\Position;

use Magento\Backend\App\Action\Context;
use Task\ProductPosition\Model\Product;

class MassDelete extends \Magento\Backend\App\Action
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

        $ids = $this->getRequest()->getParam('id');
        if (!is_array($ids) || empty($ids)) {
            $this->messageManager->addError(__('Please select product(s).'));
        } else {
            try {
                foreach ($ids as $id) {
                    $this->product->load($id);
                    $this->product->delete();
                }
                $this->messageManager->addSuccess(
                    __('A total of %1 record(s) have been deleted.', count($ids))
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/');
    }
}
