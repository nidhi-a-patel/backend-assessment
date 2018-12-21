<?php

namespace MyCompany\GoogleFeed\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const GOOGLEFEED_PATH_SETTING   = 'mycompany_googlefeed/settings/';
    public function getConfigValue($key)
    {
        return $this->scopeConfig->getValue(
            self::GOOGLEFEED_PATH_SETTING.$key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
