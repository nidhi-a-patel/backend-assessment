<?php

namespace MyCompany\GoogleFeed\Model;

class PriceConverter 
{
    private $curl;
    private $store;
    private $logger;
    private $helper;

    public function __construct(
        \Magento\Framework\HTTP\Client\Curl $curl, 
        \Magento\Store\Model\StoreManagerInterface $store,
        \Psr\Log\LoggerInterface $logger,
        \MyCompany\GoogleFeed\Helper\Data $helper
    )
    {
        $this->curl = $curl;
        $this->store = $store;
        $this->logger = $logger;
        $this->helper = $helper;
    }

    public function getConversionRate()
    {
        try {
            $apiUrl = $this->helper->getApiConfigValue('api_url');
            $endPoint = $this->helper->getApiConfigValue('end_point');
            $apiKey = $this->helper->getApiConfigValue('api_key');
            $storeCurrency = $this->store->getStore()->getCurrentCurrency()->getCode();
            if ($apiUrl!='' && $apiKey!='' && $endPoint!='') {
                $curlUrl = sprintf("%s%s?access_key=%s", $apiUrl, $endPoint, $apiKey);
                $this->curl->get($curlUrl);
                $resultJson = $this->curl->getBody();
                if (empty($resultJson)) {
                    throw new \Exception("Connection error with Fixer when trying to convert currency",0);
                }

                $toCurrency = $this->helper->getApiConfigValue('convert_currency');
                if (isset($toCurrency) && $toCurrency!='') {
                    $result = json_decode($resultJson, true);
                    $toRate = $result['rates'][$toCurrency];
                    $fromRate = $result['rates'][$storeCurrency];
                    $conversionRate = $toRate/$fromRate;
                    return $conversionRate;
                }
                return null;
            }
        }
        catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            return null;
        } 
    }
}
