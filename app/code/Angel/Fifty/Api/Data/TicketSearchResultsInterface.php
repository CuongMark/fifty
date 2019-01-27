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

interface TicketSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Ticket list.
     * @return \Angel\Fifty\Api\Data\TicketInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Angel\Fifty\Api\Data\TicketInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
