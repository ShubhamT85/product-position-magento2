<?php
namespace Task\ProductPosition\Controller\Adminhtml\Position;

use Magento\Backend\App\Action\Context;
use Magento\Catalog\Model\ProductCategoryList;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\View\Asset\Repository;
use Task\ProductPosition\Model\ProductFactory;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_product;
    protected $_resource;
    protected $_dir;

    /**
     * @var ProductCategoryList
     */
    public $productCategory;

    public function __construct(
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        Context $context,
        Repository $assetRepo,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        ProductFactory $productPosition,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\App\ResourceConnection $resource,
        ProductCategoryList $productCategory,
        \Magento\Framework\Filesystem\DirectoryList $dir
    ) {
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->productPosition = $productPosition;
        $this->_product = $product;
        $this->_resource = $resource;
        $this->_assetRepo = $assetRepo;
        $this->_filesystem = $filesystem;
        $this->productCategory = $productCategory;
        $this->_dir = $dir;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $model = $this->productPosition->create();
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model->load($id);
                $model->setCreatedAt(date('Y-m-d H:i:s'));
            }
            try {
                $uploader = $this->_fileUploaderFactory->create(['fileId' => 'content']);
                $uploader->setAllowedExtensions(['csv']);
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);

                $mediaDirectory = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA);
                $result = $uploader->save(
                    $mediaDirectory->getAbsolutePath('test')
                );

                $data['content'] = /*$MEDIA_PATH.*/$result['file'];
            } catch (\Exception $e) {
                if ($e->getCode() == 0) {
                    $this->messageManager->addError($e->getMessage());
                }
            }
            if (isset($data['content']) && is_array($data['content'])) {
                foreach ($data['content'] as $key => $value) {
                    $data['content'] = $value;
                }
            }
            $model->setData($data);
            try {

                //position logic
                if ($data['category'] == '') {
                    $this->messageManager->addWarning(__('Please select a category'));
                    return $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                }
                $file_data = array();
                $data_fetched = array();

                $value = $this->_dir->getPath('media');
                $file_name = $value . "/test" . $data['content'];

                $file = fopen($file_name, "r");
                while (!feof($file)) {
                    $file_data[] = (fgetcsv($file));
                }
                fclose($file);
                foreach ($file_data as $key => $value) {
                    $data_fetched[$value['0']] = $value['1'];
                }

                $connection = $this->_resource->getConnection();
                foreach ($data_fetched as $key => $value) {
                    $check_for_id = $this->_product->getIdBySku($key);
                    if (($check_for_id != '') && is_numeric($value)) {
                        $proId = $check_for_id;
                        $new_position = $value;

                        //category logic
                        $categoryIds = $this->productCategory->getCategoryIds($proId);
                        $category = array();
                        if ($categoryIds) {
                            $category = array_unique($categoryIds);
                        }

                        foreach ($category as $key => $value) {
                            if ($data['category'] == $value) {
                                $matched_cat = $value;
                                $sql = "UPDATE catalog_category_product SET position = " . $new_position . " WHERE product_id = " . $proId . " AND category_id = " . $matched_cat . "";
                                $new_position_update = $connection->query($sql);
                            }
                        }
                    }
                }
                //end

                if (!isset($matched_cat)) {
                    $this->messageManager->addWarning(__('Please check selected category or uploaded file'));
                    return $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                }

                $model->save();
                if (isset($matched_cat)) {
                    $this->messageManager->addSuccessMessage(__('Products position changed on Category ID : ' . $matched_cat));
                }

                if ($this->getRequest()->getParam('back')) {
                    return $this->_redirect('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $this->_redirect('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __($e->getMessage()));
            }
            return $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $this->_redirect('*/*/');
    }
}
