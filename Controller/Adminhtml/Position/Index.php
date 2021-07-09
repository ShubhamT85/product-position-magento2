<?php
namespace Task\ProductPosition\Controller\Adminhtml\Position;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Catalog\Helper\Category;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Eav\Model\Config;
use Magento\Framework\View\Result\PageFactory;
use Magento\Store\Model\StoreManagerInterface;
use Task\ProductPosition\Model\Product;

class Index extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\Page
     */
    protected $resultPage;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Config $eavConfig,
        CategoryRepository $categoryRepository,
        Category $category,
        StoreManagerInterface $storeManager,
        Product $product,
        PageFactory $resultPageFactory,
        CollectionFactory $collection
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->product = $product;
        $this->resultPageFactory = $resultPageFactory;
        $this->_categories = $collection;
        $this->eavConfig = $eavConfig;
        $this->category = $category;
        $this->categoryRepository = $categoryRepository;
    }

    public function execute()
    {
        $this->resultPage = $this->resultPageFactory->create();
        $this->resultPage->setActiveMenu('Task_ProductPosition::grid');
        $this->resultPage->getConfig()->getTitle()->set((__('Product Position')));
        return $this->resultPage;
    }
}
