<?php

/** @var yii\web\View $this */
/** @var app\models\BookLoan $model */
/** @var app\models\Book[] $books */
/** @var app\models\Member[] $members */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;

$this->title = 'Issue Book';
$this->params['breadcrumbs'][] = ['label' => 'Book Loans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-loan-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="book-loan-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'book_id')->dropDownList(
            ArrayHelper::map($books, 'id', fn ($book) => "{$book->title} ({$book->available_copies} available)"),
            ['prompt' => 'Select a book']
        ) ?>

        <?= $form->field($model, 'member_id')->dropDownList(
            ArrayHelper::map($members, 'id', 'full_name'),
            ['prompt' => 'Select a member']
        ) ?>

        <?= $form->field($model, 'borrow_date')->input('date') ?>

        <?= $form->field($model, 'due_date')->input('date') ?>

        <div class="form-group">
            <?= Html::submitButton('Issue', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
