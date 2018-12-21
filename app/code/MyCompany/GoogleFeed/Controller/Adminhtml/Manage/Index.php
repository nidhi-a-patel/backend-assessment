<?php

namespace MyCompany\GoogleFeed\Controller\Adminhtml\Manage;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var string
     */
    private $aclResource = "MyCompany_GoogleFeed::googlefeed";

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * Class constructor
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
        )
        {
            $this->resultPageFactory = $resultPageFactory;
            parent::__construct($context);
        }

    /**
     * Action to display the google feed generate button
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu("MyCompany_GoogleFeed::googlefeed_manage");
        $resultPage->addBreadcrumb(__('Google Feed'), __('Google Feed'));
        $resultPage->getConfig()->getTitle()->prepend(__('Google Feed'));
        return $resultPage;
    }
}
