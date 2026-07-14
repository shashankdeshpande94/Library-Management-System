<?php

/** @var yii\web\View $this */
/** @var app\models\BookLoan $model */

use app\models\BookLoan;
use yii\bootstrap5\Html;
use yii\widgets\DetailView;

$this->title = 'Loan #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Book Loans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-loan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($model->status !== BookLoan::STATUS_RETURNED): ?>
            <?= Html::a('Mark as Returned', ['return', 'id' => $model->id], [
                'class' => 'btn btn-primary',
                'data' => [
                    'confirm' => 'Mark this book as returned?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this loan record?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'book_id',
                'label' => 'Book',
                'value' => $model->book->title,
            ],
            [
                'attribute' => 'member_id',
                'label' => 'Member',
                'value' => $model->member->full_name,
            ],
            'borrow_date',
            'due_date',
            'return_date',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
