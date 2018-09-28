<?php

namespace Anymarket\Anymarket\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class UpgradeSchema  implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $setup->getConnection()->newTable(
            $setup->getTable('anymarket_feed')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'nullable' => false,
                'primary' => true,
            ),
            'AnyMarket Feed ID'
        )
            ->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                array(
                    'nullable' => false,
                ),
                'Type of feed'
            )
            ->addColumn(
                'id_item',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                array(
                    'nullable' => false,
                ),
                'Id item in Feed'
            )
            ->addColumn(
                'oi',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                array(
                    'nullable' => false,
                ),
                'OI Anymarket'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                array(),
                'AnyMarket Feed Modification Time'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                array(),
                'AnyMarket Feed Creation Time'
            )
            ->setComment('AnyMarket Feed Table');
        $installer->getConnection()->createTable($table);

        $table = $setup->getConnection()->newTable(
            $setup->getTable('anymarket_order')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'nullable' => false,
                'primary' => true,
            ),
            'Anymarket Order ID'
        )
            ->addColumn(
                'id_anymarket',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                array(
                    'nullable' => false,
                ),
                'ID Order in Anymarket'
            )
            ->addColumn(
                'id_magento',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                array(
                    'nullable' => false,
                ),
                'ID Order in Magento'
            )
            ->addColumn(
                'oi',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                array(
                    'nullable' => false,
                ),
                'OI Anymarket'
            )
            ->addColumn(
                'marketplace',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255,
                array(
                    'nullable' => false,
                ),
                'Marketplace'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                array(),
                'AnyMarket Order Modification Time'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                array(),
                'AnyMarket Order Creation Time'
            )
            ->setComment('Anymarket Order Table');
        $installer->getConnection()->createTable($table);


        $installer->endSetup();

    }
}