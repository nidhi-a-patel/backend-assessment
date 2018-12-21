<?php

namespace MyCompany\GoogleFeed\Block\Adminhtml\Feed;

class Actions extends \Magento\Backend\Block\Template
{

    /**
     * @var \Magento\Framework\Authorization
     */
    protected $authorization = null;

    /**
     * @var string
     */
    private $aclResource = "generate_feed";

    /**
     * Class constructor
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    )
    {
        $this->authorization = $context->getAuthorization();
        $this->setTemplate('feed/actions.phtml');
        parent::__construct($context, $data);
    }

    public function isAllowed()
    {
        return $this->authorization->isAllowed('MyCompany_GoogleFeed::' . $this->aclResource);
    }

    public function getGenerateFeedUrl()
    {
        return $this->getUrl("*/manage/generate", ["redirect" => "googlefeed_manage_index"]);
    }

}
