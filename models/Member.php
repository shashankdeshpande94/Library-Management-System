<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "member".
 *
 * @property int $id
 * @property string $full_name
 * @property string $email
 * @property string|null $phone
 * @property string $membership_date
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property BookLoan[] $bookLoans
 */
class Member extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%member}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full_name', 'email', 'membership_date'], 'required'],
            [['membership_date', 'created_at', 'updated_at'], 'safe'],
            [['full_name', 'email'], 'string', 'max' => 150],
            [['phone'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 20],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['status'], 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'membership_date' => 'Membership Date',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[BookLoans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookLoans()
    {
        return $this->hasMany(BookLoan::class, ['member_id' => 'id']);
    }

    public static function statusList()
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }
}
