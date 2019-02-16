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

namespace Angel\Fifty\Model;

use Angel\Fifty\Api\Data\TicketInterface;
use Angel\Fifty\Api\Data\TicketInterfaceFactory;
use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Angel\Fifty\Model\Product\Type\Fifty;
use Angel\Fifty\Model\Ticket\Status;
use Magento\Catalog\Model\Product;
use Magento\Customer\Model\Customer;
use Magento\Framework\Api\DataObjectHelper;

class Ticket extends \Magento\Framework\Model\AbstractModel
{

    protected $dataObjectHelper;

    protected $_eventPrefix = 'angel_fifty_ticket';
    protected $ticketDataFactory;


    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param TicketInterfaceFactory $ticketDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Angel\Fifty\Model\ResourceModel\Ticket $resource
     * @param \Angel\Fifty\Model\ResourceModel\Ticket\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        TicketInterfaceFactory $ticketDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Angel\Fifty\Model\ResourceModel\Ticket $resource,
        \Angel\Fifty\Model\ResourceModel\Ticket\Collection $resourceCollection,
        array $data = []
    ) {
        $this->ticketDataFactory = $ticketDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve ticket model with ticket data
     * @return TicketInterface
     */
    public function getDataModel()
    {
        $ticketData = $this->getData();
        
        $ticketDataObject = $this->ticketDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $ticketDataObject,
            $ticketData,
            TicketInterface::class
        );
        
        return $ticketDataObject;
    }

    /**
     * @param Product $product
     * @return \Magento\Framework\DataObject
     */
    public function getLastTicketByProduct($product){
        return $this->getCollection()
            ->addFieldToFilter('product_id', $product->getId())
//            ->addFieldToFilter('status', ['isn' => Status::STATUS_CANCELED])
            ->setPageSize(1)
            ->setCurPage(1)
            ->setOrder('end', 'DESC')
            ->getFirstItem();
    }

    /**
     * @param $product
     * @return int|mixed
     */
    public function getLastTicketNumberByProduct($product){
        $lastTicket = $this->getLastTicketByProduct($product);
        return $lastTicket->getId()?$lastTicket->getData('end'):0;
    }
}
