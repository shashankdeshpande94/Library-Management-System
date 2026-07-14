<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int $author_id
 * @property string|null $isbn
 * @property string|null $category
 * @property int|null $published_year
 * @property int $total_copies
 * @property int $available_copies
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Author $author
 * @property BookLoan[] $bookLoans
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author_id', 'total_copies', 'available_copies'], 'required'],
            [['author_id', 'published_year', 'total_copies', 'available_copies'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 20],
            [['category'], 'string', 'max' => 100],
            [['available_copies'], 'compare', 'compareAttribute' => 'total_copies', 'operator' => '<='],
            [
                ['author_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Author::class,
                'targetAttribute' => ['author_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'author_id' => 'Author',
            'isbn' => 'ISBN',
            'category' => 'Category',
            'published_year' => 'Published Year',
            'total_copies' => 'Total Copies',
            'available_copies' => 'Available Copies',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    /**
     * Gets query for [[BookLoans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookLoans()
    {
        return $this->hasMany(BookLoan::class, ['book_id' => 'id']);
    }

    public function getIsAvailable()
    {
        return $this->available_copies > 0;
    }
}
