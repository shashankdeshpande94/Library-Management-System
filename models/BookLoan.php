<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book_loan".
 *
 * @property int $id
 * @property int $book_id
 * @property int $member_id
 * @property string $borrow_date
 * @property string $due_date
 * @property string|null $return_date
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Book $book
 * @property Member $member
 */
class BookLoan extends \yii\db\ActiveRecord
{
    const STATUS_BORROWED = 'borrowed';
    const STATUS_RETURNED = 'returned';
    const STATUS_OVERDUE = 'overdue';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%book_loan}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['book_id', 'member_id', 'borrow_date', 'due_date'], 'required'],
            [['book_id', 'member_id'], 'integer'],
            [['borrow_date', 'due_date', 'return_date', 'created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 20],
            [['status'], 'default', 'value' => self::STATUS_BORROWED],
            [['status'], 'in', 'range' => [self::STATUS_BORROWED, self::STATUS_RETURNED, self::STATUS_OVERDUE]],
            [
                ['book_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Book::class,
                'targetAttribute' => ['book_id' => 'id'],
            ],
            [
                ['member_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Member::class,
                'targetAttribute' => ['member_id' => 'id'],
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
            'book_id' => 'Book',
            'member_id' => 'Member',
            'borrow_date' => 'Borrow Date',
            'due_date' => 'Due Date',
            'return_date' => 'Return Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    /**
     * Gets query for [[Member]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    public static function statusList()
    {
        return [
            self::STATUS_BORROWED => 'Borrowed',
            self::STATUS_RETURNED => 'Returned',
            self::STATUS_OVERDUE => 'Overdue',
        ];
    }

    /**
     * Marks this loan as returned, sets the return date to today and
     * increments the available copy count on the related book.
     *
     * @return bool whether the operation succeeded
     */
    public function markReturned()
    {
        if ($this->status === self::STATUS_RETURNED) {
            return true;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->status = self::STATUS_RETURNED;
            $this->return_date = date('Y-m-d');
            if (!$this->save()) {
                $transaction->rollBack();
                return false;
            }

            $book = $this->book;
            $book->available_copies += 1;
            if (!$book->save(false)) {
                $transaction->rollBack();
                return false;
            }

            $transaction->commit();
            return true;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
