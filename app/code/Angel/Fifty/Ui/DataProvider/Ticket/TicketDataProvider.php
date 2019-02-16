<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Angel\Fifty\Ui\DataProvider\Ticket;

use Angel\Fifty\Model\ResourceModel\Ticket\Collection;
use Angel\Fifty\Model\TicketManagement;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\UrlInterface;

/**
 * Class ReviewDataProvider
 *
 * @api
 *
 * @method Collection getCollection
 * @since 100.1.0
 */
class TicketDataProvider extends AbstractDataProvider
{
    /**
     * @var CollectionFactory
     * @since 100.1.0
     */
    protected $collectionFactory;

    /**
     * @var RequestInterface
     * @since 100.1.0
     */
    protected $request;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var TicketManagement
     */
    protected $ticketManagement;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        Collection $collection,
        RequestInterface $request,
        UrlInterface $urlBuilder,
        TicketManagement $ticketManagement,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collection;
        $this->request = $request;
        $this->urlBuilder = $urlBuilder;
        $this->ticketManagement = $ticketManagement;
    }

    /**
     * {@inheritdoc}
     * @since 100.1.0
     */
    public function getData()
    {
        $collection = $this->getCollection();
        $this->ticketManagement->joinProductName($collection);
        $this->ticketManagement->joinCustomerEmail($collection);
        $collection->addFieldToSelect('*');
        foreach ($this->getCollection() as $item) {
            $arrItems['items'][] = $item->toArray([]);
        }
        return $arrItems;
    }

    /**
     * {@inheritdoc}
     * @since 100.1.0
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        parent::addFilter($filter);
    }
}
