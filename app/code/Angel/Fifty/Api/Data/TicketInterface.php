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

namespace Angel\Fifty\Api\Data;

interface TicketInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const INVOICE_ITEM_ID = 'invoice_item_id';
    const CREATED_AT = 'created_at';
    const TICKET_ID = 'ticket_id';
    const CREDIT_TRANSACTION_ID = 'credit_transaction_id';
    const START = 'start';
    const COMMENT = 'comment';
    const PRICE = 'price';
    const STATUS = 'status';
    const END = 'end';
    const PRODUCT_ID = 'product_id';
    const CUSTOMER_ID = 'customer_id';

    /**
     * Get ticket_id
     * @return string|null
     */
    public function getTicketId();

    /**
     * Set ticket_id
     * @param int $ticketId
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setTicketId($ticketId);

    /**
     * Get product_id
     * @return int|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param int $productId
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setProductId($productId);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\Fifty\Api\Data\TicketExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Angel\Fifty\Api\Data\TicketExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\Fifty\Api\Data\TicketExtensionInterface $extensionAttributes
    );

    /**
     * Get customer_id
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set customer_id
     * @param int $customerId
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get start
     * @return int|null
     */
    public function getStart();

    /**
     * Set start
     * @param int $start
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setStart($start);

    /**
     * Get end
     * @return int|null
     */
    public function getEnd();

    /**
     * Set end
     * @param int $end
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setEnd($end);

    /**
     * Get status
     * @return int|null
     */
    public function getStatus();

    /**
     * Set status
     * @param int $status
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setStatus($status);

    /**
     * Get invoice_item_id
     * @return int|null
     */
    public function getInvoiceItemId();

    /**
     * Set invoice_item_id
     * @param int $invoiceItemId
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setInvoiceItemId($invoiceItemId);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get credit_transaction_id
     * @return string|null
     */
    public function getCreditTransactionId();

    /**
     * Set credit_transaction_id
     * @param string $creditTransactionId
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setCreditTransactionId($creditTransactionId);

    /**
     * Get comment
     * @return string|null
     */
    public function getComment();

    /**
     * Set comment
     * @param string $comment
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setComment($comment);

    /**
     * Get price
     * @return float|null
     */
    public function getPrice();

    /**
     * Set price
     * @param float $price
     * @return \Angel\Fifty\Api\Data\TicketInterface
     */
    public function setPrice($price);
}