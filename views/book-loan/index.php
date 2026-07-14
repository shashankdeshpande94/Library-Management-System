<?php

/** @var yii\web\View $this */
/** @var app\models\BookLoanSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\models\BookLoan;
use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = 'Book Loans';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-loan-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Issue Book', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'book_id',
                'label' => 'Book',
                'value' => fn ($data) => $data->book->title,
            ],
            [
                'attribute' => 'member_id',
                'label' => 'Member',
                'value' => fn ($data) => $data->member->full_name,
            ],
            'borrow_date',
            'due_date',
            'return_date',
            [
                'attribute' => 'status',
                'filter' => BookLoan::statusList(),
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {return} {delete}',
                'buttons' => [
                    'return' => function ($url, $model) {
                        if ($model->status === BookLoan::STATUS_RETURNED) {
                            return '';
                        }

                        return Html::a('Return', ['return', 'id' => $model->id], [
                            'class' => 'text-decoration-none',
                            'title' => 'Return book',
                            'data' => [
                                'confirm' => 'Mark this book as returned?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
