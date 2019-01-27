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

interface PrizeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Prize list.
     * @return \Angel\Fifty\Api\Data\PrizeInterface[]
     */
    public function getItems();

    /**
     * Set winning_number list.
     * @param \Angel\Fifty\Api\Data\PrizeInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
