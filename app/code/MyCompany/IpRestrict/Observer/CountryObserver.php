<?php
namespace MyCompany\IpRestrict\Observer;
 
class CountryObserver implements \Magento\Framework\Event\ObserverInterface
{
     /**
     * @var \Magento\Framework\UrlFactory
     */
    protected $urlFactory;

     /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $response;

     /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

     /**
     * @var \MyCompany\IpRestrict\Model\Country
     */
    protected $modelCountry;

    public function __construct(
        \Magento\Framework\UrlFactory $urlFactory, 
        \Magento\Framework\App\Response\Http $response,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \MyCompany\IpRestrict\Model\Country $country
    )
    {
        $this->urlFactory = $urlFactory;
        $this->response = $response;
        $this->scopeConfig = $scopeConfig;
        $this->modelCountry = $country;
    }
    
    /**
     * Below is the method that will fire whenever the event runs!
     * observer prevent website access from China and Russia, And if so send to default error page
     * @param Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $enable = $this->scopeConfig->getValue(\MyCompany\IpRestrict\Helper\Data::XML_PATH_RESTRICT_ENABLED, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
        $countryStr = $this->scopeConfig->getValue(\MyCompany\IpRestrict\Helper\Data::XML_PATH_RESTRICTED_COUNTRY, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
        if ($enable && strlen($countryStr)>0) {   
            $request = $observer->getEvent()->getRequest();
            $response = $observer->getEvent()->getResponse();
            $actionFullName = strtolower($request->getFullActionName());
            $objCountry = $this->modelCountry->getCountryCode();
            $redirectUrl = $this->scopeConfig->getValue(\MyCompany\IpRestrict\Helper\Data::XML_PATH_DEFAULT_NO_ROUTE, \Magento\Store\Model\ScopeInterface::SCOPE_STORES);
            $countryArray = explode(',',$countryStr);
            echo in_array($objCountry->geoplugin_countryCode,$countryArray);
            if (in_array($objCountry->geoplugin_countryCode,$countryArray) && $actionFullName!=str_replace('/','_',$redirectUrl)) {
                $this->response->setRedirect( $this->urlFactory->create()->getUrl($redirectUrl));
            }
        }
    }
 }
 