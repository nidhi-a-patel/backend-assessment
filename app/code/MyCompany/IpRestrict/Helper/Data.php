<?php
namespace MyCompany\IpRestrict\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * CMS Default no-route config path
     */
    const XML_PATH_DEFAULT_NO_ROUTE = 'web/default/no_route';
    /**
     * CMS Default no-route config path
     */
    const XML_PATH_RESTRICT_ENABLED = 'mycompany_iprestrict/settings/enabled';

    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_RESTRICT_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
