<?php
namespace Task\ProductPosition\Controller\Adminhtml\Position;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Task\ProductPosition\Model\Product;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */

    public function __construct(
        Context $context,
        Product $product,
        Registry $registry
    ) {
        $this->product = $product;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->product;
        $registryObject = $this->_coreRegistry;
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This row no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        $registryObject->register('product_productposition', $model);
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
