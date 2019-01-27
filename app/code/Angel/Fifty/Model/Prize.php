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

namespace Angel\Fifty\Model;

use Angel\Fifty\Api\Data\PrizeInterface;
use Angel\Fifty\Api\Data\PrizeInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;

class Prize extends \Magento\Framework\Model\AbstractModel
{

    protected $prizeDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'angel_fifty_prize';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param PrizeInterfaceFactory $prizeDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Angel\Fifty\Model\ResourceModel\Prize $resource
     * @param \Angel\Fifty\Model\ResourceModel\Prize\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        PrizeInterfaceFactory $prizeDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Angel\Fifty\Model\ResourceModel\Prize $resource,
        \Angel\Fifty\Model\ResourceModel\Prize\Collection $resourceCollection,
        array $data = []
    ) {
        $this->prizeDataFactory = $prizeDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve prize model with prize data
     * @return PrizeInterface
     */
    public function getDataModel()
    {
        $prizeData = $this->getData();
        
        $prizeDataObject = $this->prizeDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $prizeDataObject,
            $prizeData,
            PrizeInterface::class
        );
        
        return $prizeDataObject;
    }
}
