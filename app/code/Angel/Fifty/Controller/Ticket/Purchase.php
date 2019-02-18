<?php
/**
 * Angel Fifty Raffles
 * Copyright (C) 2018 Mark Wolf
 * 
 * This file included in Angel/Fifty is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Angel\Fifty\Controller\Ticket;

use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Angel\Fifty\Model\Product\Type\Fifty;
use Angel\Fifty\Model\Ticket;
use Angel\Fifty\Model\TicketFactory;
use Angel\Fifty\Model\TicketRepository;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\Model\Session;
use Magento\Framework\Exception\NoSuchEntityException;

class Purchase extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $jsonHelper;
    /**
     * @var TicketFactory
     */
    protected $ticketFactory;
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context  $context
     * @param \Magento\Framework\Json\Helper\Data $jsonHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        TicketFactory $ticketFactory,
        TicketRepository $ticketRepository,
        ProductRepository $productRepository,
        Session $customerSession
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonHelper = $jsonHelper;
        $this->ticketRepository = $ticketRepository;
        $this->ticketFactory = $ticketFactory;
        $this->productRepository = $productRepository;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $id = $this->getRequest()->getParam('id');
            $qty = (int)$this->getRequest()->getParam('qty');
            if ($qty > 0) {
                $product = $this->productRepository->getById($id);
                $customer = $this->customerSession->getCustomer();
                /** @var Fifty $productTypeInstance */
                $productTypeInstance = $product->getTypeInstance();
                /** @var \Angel\Fifty\Model\Data\Ticket $newTicketData */
                $newTicketData = $productTypeInstance->createTicket($product, $customer, $qty);
                $message = $qty==1?__('You purchaed successfully a ticket. The ticket number is %1', $newTicketData->getStart())
                    :__('You purchaed successfully %1 tickets. The ticket numbers is from %2 to %3', $qty, $newTicketData->getStart(), $newTicketData->getEnd());
                return $this->jsonResponse([
                    'success' => true,
                    'data' => $newTicketData->__toArray(),
                    'message' => $message
                ]);
            } else {
                throw new \Exception('Qty is not available');
            }
        } catch (NoSuchEntityException $e) {
            return $this->jsonResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ]
            );
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->jsonResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ]
            );
        } catch (\Exception $e) {
            return $this->jsonResponse(
                [
                    'success' => false,
                    'message' => $e->getMessage()
                ]
            );
        }
    }

    /**
     * Create json response
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function jsonResponse($response = '')
    {
        return $this->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($response)
        );
    }
}
