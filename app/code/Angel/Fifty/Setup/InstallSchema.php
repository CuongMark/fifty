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

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        //Your install script

        $table_angel_fifty_ticket = $setup->getConnection()->newTable($setup->getTable('angel_fifty_ticket'));

        $table_angel_fifty_ticket->addColumn(
            'ticket_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_angel_fifty_ticket->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product Id'
        );

        $table_angel_fifty_ticket->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'Customer Id'
        );

        $table_angel_fifty_ticket->addColumn(
            'start',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'Start Number'
        );

        $table_angel_fifty_ticket->addColumn(
            'end',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'End Number'
        );

        $table_angel_fifty_ticket->addColumn(
            'price',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['precision' => 12,'scale' => 4],
            'Ticket Price'
        );

        $table_angel_fifty_ticket->addColumn(
            'invoice_item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [],
            'Invoice Item Id'
        );

        $table_angel_fifty_ticket->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['default' =>  new \Zend_Db_Expr('CURRENT_TIMESTAMP'),'nullable' => False],
            'Created At'
        );

        $table_angel_fifty_ticket->addColumn(
            'credit_transaction_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Store Credit Transaction Id'
        );

        $table_angel_fifty_ticket->addColumn(
            'comment',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            1023,
            [],
            'Comment'
        );

        $table_angel_fifty_ticket->addColumn(
            'status',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            ['default' => '0','nullable' => False],
            'Status'
        );

        $table_angel_fifty_ticket->addForeignKey(
            $setup->getFkName('angel_fifty_ticket', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $table_angel_fifty_prize = $setup->getConnection()->newTable($setup->getTable('angel_fifty_prize'));

        $table_angel_fifty_prize->addColumn(
            'prize_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_angel_fifty_prize->addColumn(
            'winning_number',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => False],
            'Winning Number'
        );

        $table_angel_fifty_prize->addColumn(
            'winning_prize',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '12,4',
            ['nullable' => False,'precision' => 12,'scale' => 4],
            'winning_prize'
        );

        $table_angel_fifty_prize->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'Product Id'
        );

        $table_angel_fifty_prize->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            ['default' =>  new \Zend_Db_Expr('CURRENT_TIMESTAMP'),'nullable' => False],
            'Created At'
        );

        $table_angel_fifty_prize->addForeignKey(
            $setup->getFkName('angel_fifty_prize', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $setup->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        );

        $setup->getConnection()->createTable($table_angel_fifty_prize);

        $setup->getConnection()->createTable($table_angel_fifty_ticket);
    }
}
