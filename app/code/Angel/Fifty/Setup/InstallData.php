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

namespace Angel\Fifty\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\InstallDataInterface;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;
    private $customerSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Customer\Setup\CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->customerSetupFactory = $customerSetupFactory;

        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
//        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);
//
//        $customerSetup->addAttribute(\Magento\Customer\Model\Customer::ENTITY, 'nick_name', [
//            'type' => 'varchar',
//            'label' => 'Nick Name',
//            'input' => 'text',
//            'source' => '',
//            'required' => true,
//            'visible' => true,
//            'position' => 333,
//            'system' => false,
//            'backend' => ''
//        ]);
//
//        $attribute = $customerSetup->getEavConfig()->getAttribute('customer', 'nick_name')
//        ->addData(['used_in_forms' => [
//                'adminhtml_customer',
//                'customer_account_create',
//                'customer_account_edit'
//            ]
//        ]);
//        $attribute->save();

        //Your install script

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        // associate these attributes with new product type
        $fieldList = [
            'price',
//            'special_price',
//            'special_from_date',
//            'special_to_date',
//            'minimal_price',
//            'cost',
//            'tier_price',
//            'weight',
        ];
        
        // make these attributes applicable to new product type
        foreach ($fieldList as $field) {
            $applyTo = explode(
                ',',
                $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $field, 'apply_to')
            );
            if (!in_array(\Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID, $applyTo)) {
                $applyTo[] = \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID;
                $eavSetup->updateAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    $field,
                    'apply_to',
                    implode(',', $applyTo)
                );
            }
        }

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'fifty_start_at',
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => '',
                'label' => 'Start At',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => true,
                'default' =>  new \Zend_Db_Expr('CURRENT_TIMESTAMP'),
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'fifty_finish_at',
            [
                'type' => 'datetime',
                'backend' => '',
                'frontend' => '',
                'label' => 'Finish At',
                'input' => 'date',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'start_pot',
            [
                'type' => 'decimal',
                'backend' => '',
                'frontend' => '',
                'label' => 'Start Pot',
                'input' => 'price',
                'class' => '',
                'source' => '',
                'global' => 1,
                'visible' => true,
                'required' => true,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'raffle_prefix',
            [
                'type' => 'varchar',
                'backend' => '',
                'frontend' => '',
                'label' => 'Prefix',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'fifty_status',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Raffle Status',
                'input' => 'select',
                'class' => '',
                'source' => \Angel\Fifty\Model\Product\Attribute\Source\FiftyStatus::class,
                'global' => 1,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => null,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => \Angel\Fifty\Model\Product\Type\Fifty::TYPE_ID,
                'system' => 1,
                'group' => 'General',
                'option' => array('values' => array(""))
            ]
        );
    }
}
