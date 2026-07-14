<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%book}}`.
 */
class m260713_102008_create_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'author_id' => $this->integer()->notNull(),
            'isbn' => $this->string(20)->null(),
            'category' => $this->string(100)->null(),
            'published_year' => $this->integer()->null(),
            'total_copies' => $this->integer()->notNull()->defaultValue(1),
            'available_copies' => $this->integer()->notNull()->defaultValue(1),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->createIndex('idx-book-author_id', '{{%book}}', 'author_id');
        $this->addForeignKey(
            'fk-book-author_id',
            '{{%book}}',
            'author_id',
            '{{%author}}',
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
        $this->dropForeignKey('fk-book-author_id', '{{%book}}');
        $this->dropIndex('idx-book-author_id', '{{%book}}');
        $this->dropTable('{{%book}}');
    }
}
