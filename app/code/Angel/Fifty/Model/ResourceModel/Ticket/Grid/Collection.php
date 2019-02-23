<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\Fifty\Model\ResourceModel\Ticket\Grid;

use Angel\Fifty\Model\TicketManagement;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Psr\Log\LoggerInterface as Logger;

/**
 * Order grid collection
 */
class Collection extends \Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult
{
    /**
     * @var RequestInterface
     */
    private $request;
    private $ticketManagement;

    /**
     * Initialize dependencies.
     *
     * @param EntityFactory $entityFactory
     * @param Logger $logger
     * @param FetchStrategy $fetchStrategy
     * @param EventManager $eventManager
     * @param string $mainTable
     * @param string $resourceModel
     */
    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        RequestInterface $request,
        TicketManagement $ticketManagement,
        $mainTable = 'angel_fifty_ticket',
        $resourceModel = \Angel\Fifty\Model\ResourceModel\Ticket::class
    ) {
        $this->request = $request;
        $this->ticketManagement = $ticketManagement;
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel);
    }

    /**
     * Initialize select
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->_joinFields();
        $this->_addFilters();
        return $this;
    }

    protected function _joinFields()
    {
        $this->ticketManagement->joinProductName($this);
        $this->ticketManagement->joinCustomerEmail($this);
        $this->ticketManagement->joinPrize($this);
    }

    protected function _addFilters()
    {
        if ($this->request->getParam('current_product_id'))
            $this->addFieldToFilter('main_table.product_id', $this->request->getParam('current_product_id'));
    }


}
