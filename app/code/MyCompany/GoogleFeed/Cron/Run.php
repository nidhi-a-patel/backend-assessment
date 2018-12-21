<?php 

namespace MyCompany\GoogleFeed\Cron;

class Run 
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    private $dir;

    /**
     * @var \MyCompany\GoogleFeed\Model\GoogleFeed
     */
    private $googlefeed;
	
    public function __construct(
      \Psr\Log\LoggerInterface $logger, 
      \Magento\Framework\Filesystem\DirectoryList $dir,
      \MyCompany\GoogleFeed\Model\GoogleFeed $googlefeed
      
    ) {
        $this->logger = $logger;
	    $this->dir = $dir->getRoot();
	    $this->googlefeed = $googlefeed;
    }

    public function execute()
    {
        //Edit it according to your requirement
        $content = $this->googlefeed->getFeed();
		$dir = $this->dir;
        $fileName = 'googlefeed.xml';
        $myfile = fopen($dir . '/' . $fileName, "w") or die("Unable to open file!");
        try {
            fwrite($myfile, $content);
            fclose($myfile);
        } catch (Exception $e) {
            $this->logger($e->getMessage());
        }
        return $this;
    }
}
