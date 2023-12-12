<?php
/**
* @package Ceymox_SecondAssessment
*/
declare(strict_types=1);

namespace Ceymox\SecondAssessment\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;

class ForceCustomerLoginObserver implements ObserverInterface
{
    protected $responseFactory;

    protected $messageManager;

    protected $url;

    private $scopeConfig;

    private $customerSession;

    private $customerUrl;

    private $context;

    private $contextHttp;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Http\Context $contextHttp,
        \Magento\Customer\Model\Url $customerUrl,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url
    ) {
        $this->scopeConfig     = $scopeConfig;
        $this->context         = $context;
        $this->customerSession = $customerSession;
        $this->contextHttp     = $contextHttp;
        $this->customerUrl     = $customerUrl;
        $this->messageManager = $context->getMessageManager();
        $this->messageManager = $messageManager;
        $this->responseFactory = $responseFactory;        
        $this->url = $url;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // $item = $observer->getEvent()->getData('quote_item');
        // $product = $item->getProduct();   
        // $price = $product->getPrice();
        // $item = ( $item->getParentItem() ? $item->getParentItem() : $item );
        if (!$this->customerSession->isLoggedIn()) {
        // if (!$this->customerSession->isLoggedIn() && $price>100) {
            $observer->getRequest()->setParam('product', false);
            $this->messageManager->addErrorMessage(__('You need to log-in for adding any product worth 100 or more.'));
        }
    }
}