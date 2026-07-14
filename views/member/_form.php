<?php

/** @var yii\web\View $this */
/** @var app\models\Member $model */
/** @var yii\bootstrap5\ActiveForm $form */

use app\models\Member;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<div class="member-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'membership_date')->input('date') ?>

    <?= $form->field($model, 'status')->dropDownList(Member::statusList()) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
