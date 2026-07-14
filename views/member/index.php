<?php

/** @var yii\web\View $this */
/** @var app\models\MemberSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\models\Member;
use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = 'Members';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="member-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Add Member', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'full_name',
            'email:email',
            'phone',
            'membership_date',
            [
                'attribute' => 'status',
                'filter' => Member::statusList(),
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
