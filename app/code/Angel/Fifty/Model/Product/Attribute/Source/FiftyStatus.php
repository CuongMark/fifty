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

namespace Angel\Fifty\Model\Product\Attribute\Source;

class FiftyStatus extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{

    const STATUS_PENDING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_FINISHED = 2;
    const STATUS_CANCELED = 3;
    /**
     * getAllOptions
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
        ['value' => self::STATUS_PENDING, 'label' => __('Pending')],
        ['value' => self::STATUS_PROCESSING, 'label' => __('Processing')],
        ['value' => self::STATUS_FINISHED, 'label' => __('Finished')],
        ['value' => self::STATUS_CANCELED, 'label' => __('Canceled')]
        ];
        return $this->_options;
    }

    public static function OptionsArray(){
        return [
            ['value' => self::STATUS_PENDING, 'label' => __('Pending')],
            ['value' => self::STATUS_PROCESSING, 'label' => __('Processing')],
            ['value' => self::STATUS_FINISHED, 'label' => __('Finished')],
            ['value' => self::STATUS_CANCELED, 'label' => __('Canceled')]
        ];
    }

    public static function Options(){
        return [
            [self::STATUS_PENDING => __('Pending')],
            [self::STATUS_PROCESSING => __('Processing')],
            [self::STATUS_FINISHED => __('Finished')],
            [self::STATUS_CANCELED => __('Canceled')]
        ];
    }

    /**
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
            $attributeCode => [
                'unsigned' => false,
                'default' => null,
                'extra' => null,
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'comment' => $attributeCode . ' column',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getFlatIndexes()
    {
        $indexes = [];
        
        $index = 'IDX_' . strtoupper($this->getAttribute()->getAttributeCode());
        $indexes[$index] = ['type' => 'index', 'fields' => [$this->getAttribute()->getAttributeCode()]];
        
        return $indexes;
    }

    /**
     * @param int $store
     * @return \Magento\Framework\DB\Select|null
     */
    public function getFlatUpdateSelect($store)
    {
        return $this->eavAttrEntity->create()->getFlatUpdateSelect($this->getAttribute(), $store);
    }
}
