<?php

use yii\db\Migration;

/**
 * Class m200725_072851_product
 */
class m200725_072851_product extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(
            '{{%product}}',
            [
                'id' => $this->primaryKey(),
                'image' => $this->string(),
                'is_deleted' => $this->boolean()->defaultValue(0)
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
   
}
