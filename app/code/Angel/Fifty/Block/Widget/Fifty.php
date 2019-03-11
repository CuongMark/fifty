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

namespace Angel\Fifty\Block\Widget;

use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Angel\Fifty\Model\TicketManagement;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Url\Helper\Data;
use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class Fifty extends \Magento\Catalog\Block\Product\ListProduct
{
    protected $collectionFactory;
    protected $_productCollection;
    private $ticketManagement;
    private $priceCurrency;

    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        CollectionFactory $collectionFactory,
        TicketManagement $ticketManagement,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ){
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
        $this->collectionFactory = $collectionFactory;
        $this->ticketManagement = $ticketManagement;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return PriceCurrencyInterface
     */
    public function getPriceCurrency(){
        return $this->priceCurrency;
    }

    protected function _getProductCollection()
    {
        return $this->getProducts();
    }

    public function getLoadedProductCollection() {
        return $this->getProducts();
    }

    protected function getProducts(){
        if (!$this->_productCollection) {
            $collection = $this->collectionFactory->create();
            $collection->addAttributeToSelect('*');
            $collection->addAttributeToSelect(['fifty_status']);
            $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
            $collection->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
            $collection->addFieldToFilter('type_id', \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID);
            $collection->addStoreFilter($this->_storeManager->getStore()->getId());
            $collection->addAttributeToFilter('fifty_status', FiftyStatus::STATUS_PROCESSING);
            $collection->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('small_image')
                ->addAttributeToSelect('thumbnail');
            $collection->getSelect()->joinLeft(
                ['ticket_total_price' => new \Zend_Db_Expr('('.$this->ticketManagement->getTotalTicketPriceCollection()->getSelect()->__toString().')')],
                'e.entity_id = ticket_total_price.product_id',
                ['total_ticket_price' => 'ticket_total_price.total_price',]
            );
            $collection->setOrder('fifty_finish_at', 'ASC');
            $collection->setCurPage(1)->setPageSize($this->getProductCount());
            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    protected $_template = "widget/fifty.phtml";

}
