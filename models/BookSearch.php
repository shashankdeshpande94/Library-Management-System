<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BookSearch represents the model behind the search form of `app\models\Book`.
 */
class BookSearch extends Book
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'author_id', 'published_year', 'total_copies', 'available_copies'], 'integer'],
            [['title', 'isbn', 'category'], 'safe'],
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
        $query = Book::find()->joinWith(['author']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['title' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'book.id' => $this->id,
            'book.author_id' => $this->author_id,
            'book.published_year' => $this->published_year,
            'book.total_copies' => $this->total_copies,
            'book.available_copies' => $this->available_copies,
        ]);

        $query->andFilterWhere(['like', 'book.title', $this->title])
            ->andFilterWhere(['like', 'book.isbn', $this->isbn])
            ->andFilterWhere(['like', 'book.category', $this->category]);

        return $dataProvider;
    }
}
