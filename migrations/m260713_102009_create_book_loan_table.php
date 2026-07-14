<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book_loan}}`.
 */
class m260713_102009_create_book_loan_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book_loan}}', [
            'id' => $this->primaryKey(),
            'book_id' => $this->integer()->notNull(),
            'member_id' => $this->integer()->notNull(),
            'borrow_date' => $this->date()->notNull(),
            'due_date' => $this->date()->notNull(),
            'return_date' => $this->date()->null(),
            'status' => $this->string(20)->notNull()->defaultValue('borrowed'), // borrowed | returned | overdue
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-book_loan-book_id', '{{%book_loan}}', 'book_id');
        $this->createIndex('idx-book_loan-member_id', '{{%book_loan}}', 'member_id');

        $this->addForeignKey(
            'fk-book_loan-book_id',
            '{{%book_loan}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-book_loan-member_id',
            '{{%book_loan}}',
            'member_id',
            '{{%member}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-book_loan-book_id', '{{%book_loan}}');
        $this->dropForeignKey('fk-book_loan-member_id', '{{%book_loan}}');
        $this->dropTable('{{%book_loan}}');
    }
}
