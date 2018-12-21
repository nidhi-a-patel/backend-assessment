<?php

namespace MyCompany\GoogleFeed\Controller\Adminhtml\Manage;

class Generate extends \Magento\Backend\App\Action
{
	/**
     * @var \MyCompany\GoogleFeed\Model\GoogleFeed
     */
    private $googleFeed;
	
	public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MyCompany\GoogleFeed\Model\GoogleFeed $googleFeed
    ) {
        $this->googleFeed = $googleFeed;
        parent::__construct($context);
    }
	
    public function execute()
    {
		$content = $this->googleFeed->getFeed();
        echo $content;
		return;
    }
}
