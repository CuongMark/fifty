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

namespace Angel\Fifty\Model\Product\Type;

use Angel\Fifty\Api\PrizeRepositoryInterface;
use Angel\Fifty\Model\Prize;
use Angel\Fifty\Model\PrizeFactory;
use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Angel\Fifty\Model\ResourceModel\Ticket\Collection;
use Angel\Fifty\Model\ResourceModel\Ticket\CollectionFactory;
use Angel\Fifty\Model\Ticket;
use Angel\Fifty\Model\TicketFactory;
use Angel\Fifty\Model\Ticket\Status;
use Angel\Fifty\Model\TicketManagement;
use Angel\Fifty\Model\TicketRepository;
use Angel\Fifty\Service\EmailFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;

class Fifty extends \Magento\Catalog\Model\Product\Type\Virtual
{
    /**
     * @var CollectionFactory
     */
    protected $_ticketCollectionFactory;

    /**
     * @var TicketFactory
     */
    protected $_ticketFactory;

    /**
     * @var TicketRepository
     */
    protected $_ticketRespository;

    /**
     * @var TicketManagement
     */
    protected $_ticketManagement;

    /**
     * @var PrizeFactory
     */
    protected $_prizeFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_prizeRespository;

    /**
     * @var EmailFactory
     */
    protected $_emailServiceFactory;

    /**
     * @var Prize
     */
    protected $prize;

    public function __construct(
        \Magento\Catalog\Model\Product\Option $catalogProductOption,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\Product\Type $catalogProductType,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\MediaStorage\Helper\File\Storage\Database $fileStorageDb,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Registry $coreRegistry,
        \Psr\Log\LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        CollectionFactory $ticketCollectionFactory,
        TicketFactory $ticketFactory,
        TicketRepository $ticketRepository,
        TicketManagement $ticketManagement,
        PrizeFactory $prizeFactory,
        PrizeRepositoryInterface $prizeRepository,
        EmailFactory $emailServiceFactory,
        \Magento\Framework\Serialize\Serializer\Json $serializer = null
    ){
        parent::__construct($catalogProductOption, $eavConfig, $catalogProductType, $eventManager, $fileStorageDb, $filesystem, $coreRegistry, $logger, $productRepository, $serializer);
        $this->_ticketCollectionFactory = $ticketCollectionFactory;
        $this->_ticketFactory = $ticketFactory;
        $this->_ticketRespository = $ticketRepository;
        $this->_ticketManagement = $ticketManagement;
        $this->_prizeFactory = $prizeFactory;
        $this->_prizeRespository = $prizeRepository;
        $this->_emailServiceFactory = $emailServiceFactory;
    }

    const TYPE_ID = 'fifty';

    /**
     * @param Product $product
     * @param Customer $customer
     * @param int $qty
     * @return \Angel\Fifty\Api\Data\TicketInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createTicket($product, $customer, $qty){
        if ($product->getTypeId() != self::TYPE_ID) {
            throw new \Exception('The product is not 50/50 raffle.');
        } elseif ($product->getData('fifty_status') != FiftyStatus::STATUS_PROCESSING){
            throw new \Exception('The 50/50 raffle is not processing.');
        } elseif (!$customer->getId()) {
            throw new \Exception('Customer does not exist.');
        } else {
            try {
                $lastTicketNumber = $this->getLastTicketNumberByProduct($product);

                $ticketData = [
                    'product_id' => $product->getId(),
                    'customer_id' => $customer->getId(),
                    'start' => $lastTicketNumber + 1,
                    'end' => $lastTicketNumber + $qty,
                    'price' => $qty * $product->getPrice(),
                    'product_name' => $product->getName(),
                    'status' => Status::STATUS_PENDING
                ];

                /** @var Ticket $ticket */
                $ticket = $this->_ticketFactory->create()->setData($ticketData);

                $this->_eventManager->dispatch('angel_fifty_create_new_ticket', ['ticket' => $ticket]);

                $ticketDataObject = $this->_ticketRespository->save($ticket->getDataModel());

                $ticket->setCustomerEmail($customer->getEmail());
                /** @var Email $emailService */
                $emailService = $this->_emailServiceFactory->create();
                $emailService->sendNewTicketEmail($product, $ticket);
            } catch (\Exception $e){
                throw new \Exception('Something went wrong.');
            }

            return $ticketDataObject;
        }
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function updateFiftyStatus($product){
        if ($this->isFinished($product) || $this->isCanceled($product)){
            return $product;
        } elseif ($this->isPending($product) && $this->getTimeToStart($product) <= 0){
            if(!$product->isObjectNew()) {
                $product->setFiftyStatus(FiftyStatus::STATUS_PROCESSING);
                $this->productRepository->save($product);
            }
            return $product;
        } elseif ($this->isPending($product) && $this->getTimeLeft($product) <= 0){
            if(!$product->isObjectNew()) {
                $product->setFiftyStatus(FiftyStatus::STATUS_CANCELED);
                $this->productRepository->save($product);
            }
            return $product;
        } elseif ($this->isProcessing($product) && $this->getTimeLeft($product) <= 0){
            if (!$this->hasWinningTickets($product) && $this->getTicketCollection($product)->getSize()){
                try {
                    $lastTicketNumer = $this->getLastTicketNumberByProduct($product);
                    $winningNumber = mt_rand(1, $lastTicketNumer);

                    /**
                     * Create Prize
                     */
                    $winningPrize = $this->getCurrentPot($product) / 2;
                    /** @var Prize $prize */
                    $prize = $this->_prizeFactory->create();
                    $prize->setWinningNumber($winningNumber)
                        ->setWinningPrize($winningPrize)
                        ->setProductId($product->getId());
                    $this->_prizeRespository->save($prize->getDataModel());

                    $sentEmails = [];
                    /**
                     * update winning ticket status
                     */
                    /** @var Ticket $winningTicket */
                    $winningTicket = $this->getTicketCollection($product)
                        ->addFieldToFilter('status', Status::STATUS_PENDING)
                        ->setCurPage(1)
                        ->setPageSize(1)
                        ->addFieldToFilter('start', ['lteq' => $winningNumber])
                        ->addFieldToFilter('end', ['gteq' => $winningNumber])
                        ->getFirstItem();
                    if ($winningTicket->getId()) {
                        $winningTicket->setStatus(Status::STATUS_WINNING);
                        $this->_ticketRespository->save($winningTicket->getDataModel());
                    }

                    /**
                     * send meail to winner
                     */
                    /** @var Email $emailService */
                    $emailService = $this->_emailServiceFactory->create();
                    $customerEmail = $emailService->sendWinningEmail($product, $prize, $winningTicket);
                    $sentEmails[] = $customerEmail;

                    /**
                     * update lose ticket status
                     */
                    $loseTickets = $this->getTicketCollection($product)
                        ->addFieldToFilter('status', Status::STATUS_PENDING)
                        ->setCurPage(1)
                        ->setPageSize(1)
                        ->addFieldToFilter('start', ['gt' => $winningNumber])
                        ->addFieldToFilter('end', ['lt' => $winningNumber]);
                    $this->_ticketManagement->joinCustomerEmail($loseTickets);

                    foreach ($loseTickets as $_ticket) {
                        $_ticket->setStatus(Status::STATUS_LOSE);
                        $this->_ticketRespository->save($_ticket->getDataModel());
                    }

                    /**
                     * send email to other
                     */
                    foreach ($loseTickets as $_ticket) {
                        $customerEmail = $_ticket->getCustomerEmail();
                        if (!in_array($customerEmail, $sentEmails)) {
                            $emailService->sendFinishedEmail($product, $prize, $customerEmail);
                        }
                    }

                    /**
                     * update product status
                     */
                    $product->setFiftyStatus(FiftyStatus::STATUS_FINISHED);
                    $this->productRepository->save($product);
                } catch (\Exception $e){
                    $this->_logger->error($e->getMessage());
                }
            } else {
                return $product;
            }
        } else {
            return $product;
        }
    }

    /**
     * @param Product $product
     * @return Collection
     */
    public function getTicketCollection($product){
        /** @var Collection $collection */
        $collection = $this->_ticketCollectionFactory->create();
        $collection->addFieldToFilter('product_id', $product->getId());
        return $collection;
    }

    /**
     * @param Product $product
     * @return \Magento\Framework\DataObject
     */
    public function getWinningTickets($product){
        $collection = $this->getTicketCollection($product);
        $collection->addFieldToFilter('status', Status::STATUS_WINNING);
        $collection->setPageSize(1)
            ->setCurPage(1)
            ->setOrder('ticket_id', 'DESC');
        return $collection->getFirstItem();
    }

    /**
     * @param $product
     * @return string
     */
    public function getStatusLabel($product){
        $optionsArray = FiftyStatus::OptionsArray();
        return $optionsArray[$product->getFiftyStatus()]['label']->getText();
    }

    /**
     * @param Product $product
     * @return Prize|\Magento\Framework\DataObject
     */
    public function getPrize($product){
        if (!$this->prize) {
            /** @var Prize $prize */
            $prize = $this->_prizeFactory->create();
            $collection = $prize->getCollection()->addFieldToFilter('product_id', $product->getId())
                ->setPageSize(1)
                ->setCurPage(1);
            $prize = $collection->getFirstItem();
            $this->prize = $prize;
        }
        return $this->prize;
    }

    /**
     * @param Product $product
     * @return int
     */
    public function hasWinningTickets($product){
        $collection = $this->getTicketCollection($product);
        $collection->addFieldToFilter('status', Status::STATUS_WINNING);
        $collection->setPageSize(1)
            ->setCurPage(1)
            ->setOrder('end', 'DESC')
            ->getFirstItem();
        return $collection->getSize();
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public function getCurrentPot($product){
        if (!$product->hasData('total_ticket_price')){
            $product->setTotalTicketPrice((float)$this->getTotalSales($product));
        }
        return (float)$product->getStartPot() + $product->getTotalTicketPrice();
    }

    /**
     * @param Product $product
     * @return float
     */
    public function getTotalSales($product){
        return (float)$this->_ticketManagement->getTotalTicketPrice($product);
    }

    /**
     * @param Product $product
     * @return \Magento\Framework\DataObject|Ticket
     */
    public function getLastTicketByProduct($product){
        /** @var Collection $collection */
        $collection = $this->_ticketCollectionFactory->create();
        return $collection
            ->addFieldToFilter('product_id', $product->getId())
            ->setPageSize(1)
            ->setCurPage(1)
            ->setOrder('end', 'DESC')
            ->getFirstItem();
    }

    /**
     * @param Product $product
     * @return int|mixed
     */
    public function getLastTicketNumberByProduct($product){
        $lastTicket = $this->getLastTicketByProduct($product);
        return $lastTicket->getId()?$lastTicket->getData('end'):0;
    }

    public function isProcessing($product){
        return $product->getFiftyStatus() == FiftyStatus::STATUS_PROCESSING;
    }

    public function isPending($product){
        return $product->getFiftyStatus() == FiftyStatus::STATUS_PENDING;
    }
    public function isFinished($product){
        return $product->getFiftyStatus() == FiftyStatus::STATUS_FINISHED;
    }

    public function isCanceled($product){
        return $product->getFiftyStatus() == FiftyStatus::STATUS_CANCELED;
    }

    public function getTimeLeft($product){
        $finish_at = new \DateTime($product->getFiftyFinishAt());
        $date = new \DateTime();
        return $finish_at->getTimestamp() - $date->getTimestamp();
    }

    public function getTimeToStart($product){
        $start_at = new \DateTime($product->getFiftyStartAt());
        $date = new \DateTime();
        return $start_at->getTimestamp() - $date->getTimestamp();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTypeSpecificData(\Magento\Catalog\Model\Product $product)
    {
        // method intentionally empty
    }

    public function isSalable($product)
    {
        if ($product->getFiftyStatus() != FiftyStatus::STATUS_PROCESSING){
            return false;
        }
        return parent::isSalable($product); // TODO: Change the autogenerated stub
    }

    public function isPossibleBuyFromList($product)
    {
        return false;
    }
}