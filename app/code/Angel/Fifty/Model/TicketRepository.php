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

use Angel\Fifty\Model\ResourceModel\Ticket as ResourceTicket;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Angel\Fifty\Api\Data\TicketInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Angel\Fifty\Model\ResourceModel\Ticket\CollectionFactory as TicketCollectionFactory;
use Angel\Fifty\Api\TicketRepositoryInterface;
use Angel\Fifty\Api\Data\TicketSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class TicketRepository implements TicketRepositoryInterface
{

    protected $resource;

    protected $searchResultsFactory;

    private $storeManager;

    protected $ticketFactory;

    protected $dataTicketFactory;

    protected $dataObjectHelper;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;

    protected $dataObjectProcessor;

    protected $extensibleDataObjectConverter;
    protected $ticketCollectionFactory;


    /**
     * @param ResourceTicket $resource
     * @param TicketFactory $ticketFactory
     * @param TicketInterfaceFactory $dataTicketFactory
     * @param TicketCollectionFactory $ticketCollectionFactory
     * @param TicketSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceTicket $resource,
        TicketFactory $ticketFactory,
        TicketInterfaceFactory $dataTicketFactory,
        TicketCollectionFactory $ticketCollectionFactory,
        TicketSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->ticketFactory = $ticketFactory;
        $this->ticketCollectionFactory = $ticketCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataTicketFactory = $dataTicketFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Angel\Fifty\Api\Data\TicketInterface $ticket
    ) {
        $ticketData = $this->extensibleDataObjectConverter->toNestedArray(
            $ticket,
            [],
            \Angel\Fifty\Api\Data\TicketInterface::class
        );
        
        $ticketModel = $this->ticketFactory->create()->setData($ticketData);
        
        try {
            $this->resource->save($ticketModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the ticket: %1',
                $exception->getMessage()
            ));
        }
        return $ticketModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($ticketId)
    {
        $ticket = $this->ticketFactory->create();
        $this->resource->load($ticket, $ticketId);
        if (!$ticket->getId()) {
            throw new NoSuchEntityException(__('Ticket with id "%1" does not exist.', $ticketId));
        }
        return $ticket->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->ticketCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Angel\Fifty\Api\Data\TicketInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Angel\Fifty\Api\Data\TicketInterface $ticket
    ) {
        try {
            $ticketModel = $this->ticketFactory->create();
            $this->resource->load($ticketModel, $ticket->getTicketId());
            $this->resource->delete($ticketModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Ticket: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($ticketId)
    {
        return $this->delete($this->getById($ticketId));
    }
}
