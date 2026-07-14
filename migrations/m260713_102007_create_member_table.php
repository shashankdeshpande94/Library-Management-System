<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%member}}`.
 */
class m260713_102007_create_member_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%member}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(150)->notNull(),
            'email' => $this->string(150)->notNull(),
            'phone' => $this->string(20)->null(),
            'membership_date' => $this->date()->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('active'), // active | inactive
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-member-email', '{{%member}}', 'email', true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%member}}');
    }
}
