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

namespace Angel\Fifty\Block\Index;

use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;

class Processing extends \Angel\Fifty\Block\Fifty
{
    protected function _getProductCollection()
    {
        if (!$this->_productCollection) {
            $collection = $this->productCollectionFactory->create();
            $collection->addAttributeToSelect('*');
            $collection->addAttributeToSelect(['fifty_status']);
            $collection->addAttributeToFilter('visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH);
            $collection->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);
            $collection->addFieldToFilter('type_id', \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID);
//            $collection->addStoreFilter($this->_storeManager->getStore()->getId());
            $collection->addAttributeToFilter('fifty_status', FiftyStatus::STATUS_PROCESSING);

            $collection->getSelect()->joinLeft(
                ['ticket_total_price' => new \Zend_Db_Expr('('.$this->ticketManagement->getTotalTicketPriceCollection()->getSelect()->__toString().')')],
                'e.entity_id = ticket_total_price.product_id',
                ['total_ticket_price' => 'ticket_total_price.total_price',]
            );
            $this->_productCollection = $collection;
        }
        return $this->_productCollection;
    }
}
