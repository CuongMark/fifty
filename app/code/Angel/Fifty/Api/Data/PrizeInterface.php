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

interface PrizeInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{

    const WINNING_PRIZE = 'winning_prize';
    const PRIZE_ID = 'prize_id';
    const PRODUCT_ID = 'product_id';
    const WINNING_NUMBER = 'winning_number';
    const CREATED_AT = 'created_at';

    /**
     * Get prize_id
     * @return string|null
     */
    public function getPrizeId();

    /**
     * Set prize_id
     * @param string $prizeId
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     */
    public function setPrizeId($prizeId);

    /**
     * Get winning_number
     * @return string|null
     */
    public function getWinningNumber();

    /**
     * Set winning_number
     * @param string $winningNumber
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     */
    public function setWinningNumber($winningNumber);

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\Fifty\Api\Data\PrizeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     * @param \Angel\Fifty\Api\Data\PrizeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\Fifty\Api\Data\PrizeExtensionInterface $extensionAttributes
    );

    /**
     * Get winning_prize
     * @return string|null
     */
    public function getWinningPrize();

    /**
     * Set winning_prize
     * @param string $winningPrize
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     */
    public function setWinningPrize($winningPrize);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $productId
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     */
    public function setProductId($productId);

    /**
     * Get created_at
     * @return string|null
     */
    public function getCreatedAt();

    /**
     * Set created_at
     * @param string $createdAt
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     */
    public function setCreatedAt($createdAt);
}
