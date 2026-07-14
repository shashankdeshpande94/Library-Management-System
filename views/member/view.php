<?php

/** @var yii\web\View $this */
/** @var app\models\Member $model */

use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => 'Members', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this member?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
            'email:email',
            'phone',
            'membership_date',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <h2 class="mt-4">Loan history</h2>
    <?= GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $model->bookLoans,
            'pagination' => false,
        ]),
        'columns' => [
            [
                'attribute' => 'book_id',
                'label' => 'Book',
                'value' => fn ($data) => $data->book->title,
            ],
            'borrow_date',
            'due_date',
            'return_date',
            'status',
        ],
    ]) ?>

</div>
