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

namespace Angel\Fifty\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface PrizeRepositoryInterface
{

    /**
     * Save Prize
     * @param \Angel\Fifty\Api\Data\PrizeInterface $prize
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Angel\Fifty\Api\Data\PrizeInterface $prize
    );

    /**
     * Retrieve Prize
     * @param string $prizeId
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($prizeId);

    /**
     * Retrieve Prize matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Angel\Fifty\Api\Data\PrizeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Prize
     * @param \Angel\Fifty\Api\Data\PrizeInterface $prize
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Angel\Fifty\Api\Data\PrizeInterface $prize
    );

    /**
     * Delete Prize by ID
     * @param string $prizeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($prizeId);
}
