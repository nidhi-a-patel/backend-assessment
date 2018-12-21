<?php

namespace MyCompany\GoogleFeed\Helper;

class Products extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var \Magento\Eav\ModelAttributeSetRepository
     */
    private $attributeSetRepo;

    /**
    * @var \Magento\Store\Model\StoreManagerInterface
    */
    private $storeManager;

    /**
    * @var \Magento\Catalog\Model\Product\Attribute\Source\Status
    */
    private $productStatus;

    /**
    * @var \Magento\Catalog\Model\Product\Visibility
    */
    private $productVisibility;

    /**
     * @var \MyCompany\GoogleFeed\Helper\Data
     */
    private $helper;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Eav\Model\AttributeSetRepository $attributeSetRepo,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \MyCompany\GoogleFeed\Helper\Data $helper
    )
    {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->attributeSetRepo = $attributeSetRepo;
        $this->storeManager = $storeManager;
        $this->productStatus = $productStatus;
        $this->productVisibility = $productVisibility;
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function getFilteredProducts()
    {
        $collection = $this->productCollectionFactory->create();
		$collection->addAttributeToSelect('*');
        $collection->addAttributeToFilter('status', ['eq' => $this->productStatus->getVisibleStatusIds()]);
        $collection->addAttributeToFilter('visibility', ['in' => $this->productVisibility->getVisibleInSiteIds()]);
		return $collection;
    }

    public function getAttributeSet($product)
    {
        $attributeSetId = $product->getAttributeSetId();
        $attributeSet = $this->attributeSetRepo->get($attributeSetId);
        return $attributeSet->getAttributeSetName();
    }

    public function getProductValue($product, $attributeCode)
    {
        $attributeCodeFromConfig = $this->helper->getConfig($attributeCode.'_attribute');
        $defaultValue = $this->helper->getConfig('default_'.$attributeCode);

        if (!empty($attributeCodeFromConfig)) {
            return $product->getAttributeText($attributeCodeFromConfig);
        }

        if (!empty($defaultValue)) {
            return $defaultValue;
        }
        return false;
    }

    public function getProductImageUrl($product)
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA, false).'catalog/product'.$product->getImage();
    }

    public function getCurrentCurrencySymbol()
    {
        return $this->storeManager->getStore()->getCurrentCurrencyCode();
    }
}
