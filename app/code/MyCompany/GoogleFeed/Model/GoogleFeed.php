<?php

namespace MyCompany\GoogleFeed\Model;

class GoogleFeed
{
    /**
     * @var \MyCompany\GoogleFeed\Helper\Data
     */
    public $helper;

    /**
     * @var \MyCompany\GoogleFeed\Helper\Products
     */
    public $productFeedHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $store;

    public function __construct(
        \MyCompany\GoogleFeed\Helper\Data $helper,
        \MyCompany\GoogleFeed\Helper\Products $productFeedHelper,
        \Magento\Store\Model\StoreManagerInterface $store
    ) {
        $this->helper = $helper;
        $this->productFeedHelper = $productFeedHelper;
        $this->store = $store;
    }

    public function getFeed($cData=true)
    {
        $xml = $this->getFeedHeader();
        $xml .= $this->getProductsFeed($cData);
        $xml .= $this->getFeedFooter();
		return $xml;
    }

    protected function getFeedHeader()
    {
        $link = $this->store->getStore()->getBaseUrl();
        header("Content-Type: application/xml; charset=utf-8");
        $xml =  '<?xml version="1.0"?>';
        $xml =  '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
        $xml .= '<channel>';
        $xml .= sprintf('<title>%s</title>',$this->helper->getConfigValue('channel_title'));
        $xml .= sprintf('<link>%s</link>',$link);
        $xml .= sprintf('<description>%s</description>',$this->helper->getConfigValue('channel_description'));
        return $xml;
    }

    protected function getFeedFooter()
    {
        $xml =  '</channel></rss>';
        return  $xml;
    }

    protected function getProductsFeed($cData = false)
    {
        $productCollection = $this->productFeedHelper->getFilteredProducts();
        $xml = "";
        foreach ($productCollection as $product) {
            $xml .= "<item>".$this->buildProductFeed($product, $cData)."</item>";
        }
        return $xml;
    }

    protected function buildProductFeed($product, $cData=false)
    {
        $description = $this->fixDescription($product->getDescription());
        $xml  = $this->generateNode("title", $product->getName(), $cData);
        $xml .= $this->generateNode("link", $product->getProductUrl());
        $xml .= $this->generateNode("description", $description, $cData);
        $xml .= $this->generateNode("g:image_link", $this->productFeedHelper->getProductImageUrl($product));
        $xml .= $this->generateNode("g:availability", $product->isInStock()?'in stock':'Out of Stock');
        $price = $product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();
        $xml .= $this->generateNode("g:price", $this->priceFormat($price));
        // $xml .= $this->generateNode('g:price', $this->priceFormat($product->getFinalPrice()));
        $xml .= $this->generateNode("g:id", $product->getId());
        return $xml;
    }

    protected function fixDescription($data)
    {
        $description = $data;
        $encode = mb_detect_encoding($data);
        $description = mb_convert_encoding($description, 'UTF-8', $encode);
        return $description;
    }

    protected function priceFormat($price, $floating=2, $decimal='.')
    {
        return number_format($price, $floating, $decimal,'');
    }

    protected function generateNode($nodeName, $value, $cData = false)
    {
        if (empty($value) || empty ($nodeName)) {
            return false;
        }
        $cDataStart = "";
        $cDataEnd = "";
        if ($cData === true) {
            $cDataStart = "<![CDATA[";
            $cDataEnd = "]]>";
        }
        $node = sprintf("<%s>%s</%s>",$nodeName,($cDataStart.$value.$cDataEnd),$nodeName);
        return $node;
    }
}
