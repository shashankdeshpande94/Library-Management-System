<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BookLoanSearch represents the model behind the search form of `app\models\BookLoan`.
 */
class BookLoanSearch extends BookLoan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'book_id', 'member_id'], 'integer'],
            [['borrow_date', 'due_date', 'return_date', 'status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = BookLoan::find()->joinWith(['book', 'member']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'book_loan.id' => $this->id,
            'book_loan.book_id' => $this->book_id,
            'book_loan.member_id' => $this->member_id,
            'book_loan.borrow_date' => $this->borrow_date,
            'book_loan.due_date' => $this->due_date,
            'book_loan.return_date' => $this->return_date,
            'book_loan.status' => $this->status,
        ]);

        return $dataProvider;
    }
}
