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

namespace Angel\Fifty\Block\Adminhtml\Fifty;

use Angel\Fifty\Model\Prize;
use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Angel\Fifty\Model\Ticket;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Details extends \Magento\Backend\Block\Template
{

    /**
     * @var ProductRepository
     */
    protected $getProductRespository;

    /** @var Product */
    protected $product;
    /**
     * @var CustomerRepository
     */
    protected $customerRespository;

    /**
     * @var Customer
     */
    protected $winningCustomer;

    /**
     * @var Ticket
     */
    protected $winningTicket;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;
    /**
     * Constructor
     *
     * @param \Magento\Backend\Block\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        ProductRepository $productRepository,
        CustomerRepository $customerRepository,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->getProductRespository = $productRepository;
        $this->customerRespository = $customerRepository;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface|mixed
     */
    public function getProduct(){
        try {
            if (!$this->product) {
                $productId = $this->getRequest()->getParam('id');
                $this->product = $this->getProductRespository->getById($productId);
            }
            return $this->product;
        } catch (\Exception $e){
            return false;
        }
    }

    /**
     * @return int
     */
    public function getTotalTicket(){
        return $this->getProduct()->getTypeInstance()->getLastTicketNumberByProduct($this->getProduct());
    }

    /**
     * @return mixed
     */
    public function getTotalSale(){
        return $this->getProduct()->getTypeInstance()->getTotalSales($this->getProduct());
    }

    /**
     * @return Ticket
     */
    public function getWinningTicket(){
        if (!$this->winningTicket) {
            $this->winningTicket = $this->getProduct()->getTypeInstance()->getWinningTickets($this->getProduct());
        }
        return $this->winningTicket;
    }

    /**
     * @return Prize
     */
    public function getPrize(){
        return $this->getProduct()->getTypeInstance()->getPrize($this->getProduct());
    }

    /**
     * @return float
     */
    public function getWiningPrizeFormated(){
        return $this->priceCurrency->format($this->getPrize()->getWinningPrize());
    }

    /**
     * @return bool
     */
    public function hasWinningTicket(){
        return (boolean)$this->getWinningTicket()->getId();
    }

    /**
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface|Customer|mixed
     */
    public function getWinningCustomer(){
        $winningTicket = $this->getWinningTicket();
        try {
            if (!$this->winningCustomer) {
                $this->winningCustomer = $this->getProductRespository->getById($winningTicket->getCustomerId());
            }
            return $this->winningCustomer;
        } catch (\Exception $e){
            return false;
        }
    }

    public function getStatus(){
        return $this->getProduct()->getTypeInstance()->getStatusLabel($this->getProduct());
    }
}
