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

use Angel\Fifty\Api\Data\PrizeInterface;

class Prize extends \Magento\Framework\Api\AbstractExtensibleObject implements PrizeInterface
{

    /**
     * Get prize_id
     * @return string|null
     */
    public function getPrizeId()
    {
        return $this->_get(self::PRIZE_ID);
    }

    /**
     * Set prize_id
     * @param string $prizeId
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     */
    public function setPrizeId($prizeId)
    {
        return $this->setData(self::PRIZE_ID, $prizeId);
    }

    /**
     * Get winning_number
     * @return string|null
     */
    public function getWinningNumber()
    {
        return $this->_get(self::WINNING_NUMBER);
    }

    /**
     * Set winning_number
     * @param string $winningNumber
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     */
    public function setWinningNumber($winningNumber)
    {
        return $this->setData(self::WINNING_NUMBER, $winningNumber);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     * @return \Angel\Fifty\Api\Data\PrizeExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     * @param \Angel\Fifty\Api\Data\PrizeExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(
        \Angel\Fifty\Api\Data\PrizeExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get winning_prize
     * @return string|null
     */
    public function getWinningPrize()
    {
        return $this->_get(self::WINNING_PRIZE);
    }

    /**
     * Set winning_prize
     * @param string $winningPrize
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     */
    public function setWinningPrize($winningPrize)
    {
        return $this->setData(self::WINNING_PRIZE, $winningPrize);
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
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::PRODUCT_ID, $productId);
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
     * @return \Angel\Fifty\Api\Data\PrizeInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
