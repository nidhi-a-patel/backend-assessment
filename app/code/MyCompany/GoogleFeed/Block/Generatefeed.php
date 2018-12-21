<?php

namespace MyCompany\GoogleFeed\Block;

class Generatefeed extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \MyCompany\GoogleFeed\Model\GoogleFeed
     */
    private $googlefeed;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \MyCompany\GoogleFeed\Model\GoogleFeed $googlefeed
    ) {
        $this->googlefeed = $googlefeed;
    }

    public function getGoogleFeed()
    {
        return $this->googlefeed->getFeed();
    }
}
