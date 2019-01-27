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

namespace Angel\Fifty\Model\Data;

use Angel\Fifty\Api\Data\TicketInterface;

class Ticket extends \Magento\Framework\Api\AbstractExtensibleObject implements TicketInterface
{

    /**
     * Get ticket_id
     * @return string|null
     */
    public function getTicketId()
    {
        return $this->_get(self::TICKET_ID);
    }

    /**
     * Set ticket_id
     * @param string $ticketId
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setTicketId($ticketId)
    {
        return $this->setData(self::TICKET_ID, $ticketId);
    }

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId()
    {
        return $this->_get(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\Fifty\Api\Data\TicketExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Angel\Fifty\Api\Data\TicketExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\Fifty\Api\Data\TicketExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get customer_id
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->_get(self::CUSTOMER_ID);
    }

    /**
     * Set customer_id
     * @param string $customerId
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::CUSTOMER_ID, $customerId);
    }

    /**
     * Get start
     * @return string|null
     */
    public function getStart()
    {
        return $this->_get(self::START);
    }

    /**
     * Set start
     * @param string $start
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setStart($start)
    {
        return $this->setData(self::START, $start);
    }

    /**
     * Get end
     * @return string|null
     */
    public function getEnd()
    {
        return $this->_get(self::END);
    }

    /**
     * Set end
     * @param string $end
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setEnd($end)
    {
        return $this->setData(self::END, $end);
    }

    /**
     * Get status
     * @return string|null
     */
    public function getStatus()
    {
        return $this->_get(self::STATUS);
    }

    /**
     * Set status
     * @param string $status
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * Get invoice_item_id
     * @return string|null
     */
    public function getInvoiceItemId()
    {
        return $this->_get(self::INVOICE_ITEM_ID);
    }

    /**
     * Set invoice_item_id
     * @param string $invoiceItemId
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setInvoiceItemId($invoiceItemId)
    {
        return $this->setData(self::INVOICE_ITEM_ID, $invoiceItemId);
    }

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get credit_transaction_id
     * @return string|null
     */
    public function getCreditTransactionId()
    {
        return $this->_get(self::CREDIT_TRANSACTION_ID);
    }

    /**
     * Set credit_transaction_id
     * @param string $creditTransactionId
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setCreditTransactionId($creditTransactionId)
    {
        return $this->setData(self::CREDIT_TRANSACTION_ID, $creditTransactionId);
    }

    /**
     * Get comment
     * @return string|null
     */
    public function getComment()
    {
        return $this->_get(self::COMMENT);
    }

    /**
     * Set comment
     * @param string $comment
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setComment($comment)
    {
        return $this->setData(self::COMMENT, $comment);
    }
}
