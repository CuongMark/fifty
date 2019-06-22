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

namespace Angel\Fifty\Block\Fifty;

use Angel\Fifty\Model\PrizeManagement;
use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Angel\Fifty\Model\Product\Type\Fifty;
use Magento\Catalog\Model\Product;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class Sidebar extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;
    protected  $_productCollection;
    private $prizeManagement;
    private $priceCurrency;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        PrizeManagement $prizeManagement,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->prizeManagement = $prizeManagement;
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getFinishedFifty(){
        if (!$this->_productCollection) {
            $collection = $this->productCollectionFactory->create();
            $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
            $collection->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
            $collection->addFieldToFilter('type_id', \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID);
            $collection->addStoreFilter($this->_storeManager->getStore()->getId());
            $collection->addAttributeToFilter('fifty_status', FiftyStatus::STATUS_FINISHED);
            $this->prizeManagement->joinWinningNumberAndPrice($collection);
            $collection->setCurPage(1)->setPageSize(5);
            $collection->addAttributeToSelect(['name', 'fifty_finish_at', 'current_pot'])
                ->setOrder('fifty_finish_at', 'DESC')
                ->setCurPage(1)
                ->setPageSize(5);
//            $collection->joinAttribute('fifty_end_at', 'catalog_product/fifty_end_at', 'entity_id', null, 'inner');
            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }

    /**
     * Retrieve formated price
     *
     * @param float $value
     * @return string
     */
    public function formatPrice($value, $isHtml = true)
    {
        return $this->priceCurrency->format(
            $value,
            $isHtml,
            PriceCurrencyInterface::DEFAULT_PRECISION,
            1 //Todo getStore
        );
    }

    /**
     * @param Product $product
     * @return string
     */
    public function getWinningNickName($product){
        /** @var Fifty $productTypeInstance */
        $productTypeInstance = $product->getTypeInstance();
        return $productTypeInstance->getWinningNickName($product);
    }
}
