<?php
/**
 * @package Ceymox_SecondAssessment
 */
declare(strict_types=1);

namespace Ceymox\SecondAssessment\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Message\ManagerInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Checkout\Model\Session as CheckoutSession;

class ForceCustomerLoginObserver implements ObserverInterface
{
     /**
      * @var Session
      */
    private $customerSession;
   
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     *  Construct
     *
     * @param Session $customerSession
     * @param ManagerInterface $messageManager
     * @param ProductRepository $productRepository
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        Session $customerSession,
        ManagerInterface $messageManager,
        ProductRepository $productRepository,
        CheckoutSession $checkoutSession
    ) {
        $this->customerSession = $customerSession;
        $this->messageManager = $messageManager;
        $this->productRepository = $productRepository;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Restrict add to cart on checking price and sutomer session
     *
     * @param Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $productId = $observer->getRequest()->getParam('product');
        $product = $this->productRepository->getById($productId);
        if (!$this->customerSession->isLoggedIn() && $product->getPrice() > 100) {
            $observer->getRequest()->setParam('product', false);
            $this->messageManager->
            addErrorMessage(__('You need to log in to add products worth 100 or more to your cart.'));
        }
    }
}
