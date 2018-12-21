<?php

namespace MyCompany\IpRestrict\Block\Product;
 
class CountryCode extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \MyCompany\IpRestrict\Model\Country
     */
    protected $modelCountry = null;

    /**
     * @var \MyCompany\IpRestrict\Helper\Data
     */
    protected $helper = null;
	
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
        \MyCompany\IpRestrict\Model\Country $country,
        \MyCompany\IpRestrict\Helper\Data $helper,
		array $data = []
    ) {
        $this->modelCountry = $country;
        $this->helper = $helper;
		parent::__construct($context, $data);
    }

    public function getCountryCode()
    {
        if ($this->helper->isEnabled()) {
            return $this->modelCountry->getCountryCode();
        }
        return;
    }
}
