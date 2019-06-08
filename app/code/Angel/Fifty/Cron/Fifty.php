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

namespace Angel\Fifty\Cron;

use Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus;
use Magento\Catalog\Model\Product;

class Fifty
{

    /**
     * @var \Angel\RaffleClient\Model\RaffleFactory
     */
    protected $raffleFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTimeFactory
     */
    protected $dateTimeFactory;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Stdlib\DateTime\DateTimeFactory $dateTimeFactory
    ){
        $this->logger = $logger;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->dateTimeFactory = $dateTimeFactory;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
        $collection = $this->productCollectionFactory->create()
            ->addAttributeToFilter('type_id', \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID)
            ->addAttributeToFilter('raffle_status', ['nin' => [FiftyStatus::STATUS_FINISHED, FiftyStatus::STATUS_CANCELED]])
            ->addAttributeToSelect('*');
        /** @var Product $product */
        foreach ($collection as $product){
            $product->getTypeInstance()->updateFiftyStatus($product);
        }
    }
}
