<?php

use yii\db\Migration;

/**
 * Class m200725_072904_store_product
 */
class m200725_072904_store_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%store_product}}',
            [
                'id' => $this->primaryKey(),
                'product_id' => $this->integer(),
                'product_image' => $this->string()
            ]
        );
        
        $this->addForeignKey(
            'fk_store_product_id',
            '{{%store_product}}',
            'product_id',
            '{{%product}}',
            'id'
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_store_product_id', '{{%store_product}}');
        $this->dropTable('{{%store_product}}');
    }
}
