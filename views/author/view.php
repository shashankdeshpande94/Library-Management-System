<?php

/** @var yii\web\View $this */
/** @var app\models\Author $model */

use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Authors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="author-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this author?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'bio:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <h2 class="mt-4">Books by this author</h2>
    <?= GridView::widget([
        'dataProvider' => new \yii\data\ArrayDataProvider([
            'allModels' => $model->books,
            'pagination' => false,
        ]),
        'columns' => [
            'title',
            'isbn',
            'category',
            'available_copies',
            'total_copies',
        ],
    ]) ?>

</div>
