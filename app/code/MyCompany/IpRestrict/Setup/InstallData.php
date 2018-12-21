<?php
namespace MyCompany\IpRestrict\Setup;

use Magento\Framework\Setup\InstallDataInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Cms\Model\BlockFactory
     */
    protected $blockFactory;

    public function __construct(\Psr\Log\LoggerInterface $logger, \Magento\Cms\Model\BlockFactory $blockFactory)
    {
        $this->logger = $logger;
        $this->blockFactory = $blockFactory;
    }

    public function install(\Magento\Framework\Setup\ModuleDataSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $moduleContext)
    {
        $setup->startSetup();
        $cmsBlock = [
            [
                'title' => 'Static Block for US',
                'identifier' => 'country_block_us',
                'content' => "<p><strong>Static Block Content for US</strong></p>",
                'is_active' => 1,
                'stores' => [0],
                'sort_order' => 0
            ],
            [
                'title' => 'Static Block for Global',
                'identifier' => 'country_block_global',
                'content' => "<p><strong>Static Block Content for Global</strong></p>",
                'is_active' => 1,
                'stores' => [0],
                'sort_order' => 0
            ]
        ];

        /**
         * Insert default and system pages
         */
        foreach ($cmsBlock as $data) {
            $block = $this->createBlock()->load(
                $data['identifier'],
                'identifier'
            );
            $this->logger->debug($block->getId());
            if(!$block->getId())
            {
                $this->createBlock()->setData($data)->save();
            }
        }
        $setup->endSetup();
    }

    /**
     * Create Block
     *
     * @return Block
     */
    public function createBlock()
    {
        return $this->blockFactory->create();
    }
}
