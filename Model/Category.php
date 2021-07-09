<?php

namespace Task\ProductPosition\Model;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Product category model
 */
class Category implements \Magento\Framework\Option\ArrayInterface
{
    protected $_categoryCollection;

    public function __construct(
        StoreManagerInterface $storeManager,
        CollectionFactory $categoryCollection
    ) {
        $this->_storeManager = $storeManager;
        $this->_categoryCollection = $categoryCollection;
    }

    public function toOptionArray()
    {
        $data = [
            ['value' => '', 'label' => '--Please Select Value--'],
        ];

        $categories = $this->_categoryCollection->create();
        $categories->addAttributeToSelect('*')->setStore(0);

        foreach ($categories as $category) {
            $cat1_name = $category->getName();
            $cat1_id = $category->getId();
            $data[] = ['value' => $cat1_id, 'label' => $cat1_name . ' (ID : ' . $cat1_id . ')'];
        }
        return $data;
    }
}
