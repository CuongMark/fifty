<?php
/**
 * Created by PhpStorm.
 * User: mark
 * Date: 2/14/2019
 * Time: 1:19 PM
 */
namespace Angel\Fifty\Model;

use Angel\Fifty\Model\ResourceModel\Ticket\Collection;
use Angel\Fifty\Model\ResourceModel\Ticket\CollectionFactory;
use Angel\Fifty\Model\Ticket\Status;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

class TicketManagement {

    /**
     * @var CollectionFactory
     */
    protected $ticketCollectionFactory;
    private $productCollectionFactory;

    public function __construct(
        CollectionFactory $ticketCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ){
        $this->ticketCollectionFactory = $ticketCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
    }

    /**
     * @param ProductCollection $collection
     */
    public function joinWinnerToProductCollection($collection){
        $collection->getSelect()->joinLeft(
            ['ticket' => $collection->getTable('angel_fifty_ticket')],
            'e.entity_id = ticket.product_id AND (ticket.status ='.Status::STATUS_WINNING .' OR ticket.status = ' . Status::STATUS_PAID . ')',
            ['customer_id' => 'ticket.customer_id']
        );
        $collection->getSelect()->joinLeft(
            ['customer' => $collection->getTable('customer_entity')],
            'ticket.customer_id = customer.entity_id',
            ['customer_email' => 'customer.email']
        );
        return $collection;
    }

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     */
    public function joinTotalTicketsToProductCollection($collection){
        /** @var Collection $ticketCollection */
        $ticketCollection = $this->ticketCollectionFactory->create();
        $ticketCollection->addFieldToSelect(['product_id'])
            ->getSelect()->columns([
                'total_ticket' => 'MAX(main_table.end)',
                'total_price' => 'SUM(main_table.price)'
            ])->group('main_table.product_id');

        $collection->getSelect()->joinLeft(
            ['total_ticket' => new \Zend_Db_Expr('('.$ticketCollection->getSelect()->__toString().')')],
            'total_ticket.product_id = e.entity_id',
            ['total_ticket' => 'total_ticket.total_ticket', 'total_price' => 'total_ticket.total_price']
        );
    }

    /**
     * @return Collection
     */
    public function getTotalTicketPriceCollection(){
        /** @var Collection $collection */
        $collection = $this->ticketCollectionFactory->create();
        $collection->addFieldToSelect(['product_id']);
        $collection->getSelect()
            ->columns(['total_price' => 'SUM(main_table.price)'])
            ->group('main_table.product_id');
        return $collection;
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public function getTotalTicketPrice($product){
        $collection = $this->getTotalTicketPriceCollection()
            ->addFieldToFilter('product_id', $product->getId())
            ->setCurPage(1)
            ->setPageSize(1);
        return $collection->getFirstItem()->getTotalPrice();
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function joinProductName($collection){
        $productCollection = $this->productCollectionFactory->create()
            ->addAttributeToSelect(['name']);
        $productCollection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner');
        $collection->getSelect()->joinLeft(['product' => new \Zend_Db_Expr('('.$productCollection->getSelect()->__toString().')')],
            "product.entity_id = main_table.product_id",
            ['product_name' => 'product.name']
        );
        return $collection;
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function joinNameAndStartPot($collection){
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner');
        $productCollection->joinAttribute('start_pot', 'catalog_product/start_pot', 'entity_id', null, 'inner');
        $collection->getSelect()->joinLeft(['product' => new \Zend_Db_Expr('('.$productCollection->getSelect()->__toString().')')],
            "product.entity_id = main_table.product_id",
            ['start_pot' => 'product.start_pot', 'product_name' => 'product.name']
        );
        return $collection;
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function joinPrize($collection){
        $collection->getSelect()->joinLeft(['prize' => $collection->getTable('angel_fifty_prize')],
            'prize.product_id = main_table.product_id AND (main_table.status = '.Status::STATUS_WINNING .' OR main_table.status =' . Status::STATUS_PAID .')',
            ['winning_number' => 'prize.winning_number' , 'winning_prize' => 'prize.winning_prize']
        );
        return $collection;
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function joinCustomerEmail($collection){
        $collection->getSelect()->joinLeft(['customer' => $collection->getTable('customer_entity')],
            'customer.entity_id = main_table.customer_id',
            ['customer_email' => 'customer.email']
        );
        return $collection;
    }
}