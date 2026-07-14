<?php

/** @var yii\web\View $this */
/** @var app\models\Book $model */

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this book?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'author_id',
                'label' => 'Author',
                'value' => $model->author->name,
            ],
            'title',
            'isbn',
            'category',
            'published_year',
            'total_copies',
            'available_copies',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
