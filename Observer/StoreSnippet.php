<?php
namespace Feedaty\Badge\Observer;

use Feedaty\Badge\Model\Config\Source\WebService;
use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\Registry ;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\Event\Observer;
use Feedaty\Badge\Helper\Data as DataHelp;

class StoreSnippet implements ObserverInterface
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var Feedaty\Badge\Helper\Data
     */
    protected $_dataHelper;


    /*
    *
    * Constructor
    *
    */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Registry $registry,
        StoreManagerInterface $storeManager,
        DataHelp $dataHelper
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->registry = $registry;
        $this->storeManager = $storeManager;
        $this->_dataHelper = $dataHelper;
    }


    /**
    *
    * Execute
    * @param $observer
    */
	public function execute(Observer $observer) {

        $webservice = new WebService($this->scopeConfig, $this->storeManager,$this->_dataHelper);
		$block = $observer->getBlock();

		$fdWidStorePos = $this->scopeConfig->getValue('feedaty_badge_options/widget_store/store_position', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $fdSnipStorPos = $this->scopeConfig->getValue('feedaty_microdata_options/snippet_store/store_position', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

		if ($observer->getElementName()== $fdSnipStorPos && $fdSnipStorPos != $fdWidStorePos) {
            
			$plugin_enabled = $this->scopeConfig->getValue('feedaty_microdata_options/snippet_store/snippet_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
			if($plugin_enabled!=0){

                $html = $webservice->getMerchantRichSnippet();
                $observer->getTransport()->setOutput($html);

			}
		}
	}
}