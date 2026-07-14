<?php

/** @var yii\web\View $this */

use app\models\Author;
use app\models\Book;
use app\models\BookLoan;
use app\models\Member;
use yii\bootstrap5\Html;

$this->title = 'Library Dashboard';

$stats = [
    ['label' => 'Books', 'count' => Book::find()->count(), 'url' => ['/book/index'], 'icon' => '📚'],
    ['label' => 'Authors', 'count' => Author::find()->count(), 'url' => ['/author/index'], 'icon' => '✍️'],
    ['label' => 'Members', 'count' => Member::find()->count(), 'url' => ['/member/index'], 'icon' => '🧑‍🤝‍🧑'],
    [
        'label' => 'Books Currently Borrowed',
        'count' => BookLoan::find()->where(['status' => BookLoan::STATUS_BORROWED])->count(),
        'url' => ['/book-loan/index'],
        'icon' => '📖',
    ],
];
?>
<div class="site-index">

    <div class="mb-4">
        <h1 class="display-6 fw-bold mb-1">Library Management System</h1>
        <p class="text-body-secondary">A basic Yii2 MVC demo application backed by MySQL.</p>
    </div>

    <div class="row g-3 mb-4">
        <?php foreach ($stats as $stat): ?>
            <div class="col-md-6 col-lg-3">
                <?= Html::a(
                    '<div class="card h-100 border-0 shadow-sm rounded-3">' .
                        '<div class="card-body">' .
                            '<div class="fs-2">' . $stat['icon'] . '</div>' .
                            '<div class="fs-3 fw-bold">' . $stat['count'] . '</div>' .
                            '<div class="text-body-secondary">' . Html::encode($stat['label']) . '</div>' .
                        '</div>' .
                    '</div>',
                    $stat['url'],
                    ['class' => 'text-decoration-none text-body']
                ) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <h3 class="h6 fw-bold mb-2">Quick actions</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <?= Html::a('Add Book', ['/book/create'], ['class' => 'btn btn-success btn-sm']) ?>
                        <?= Html::a('Add Author', ['/author/create'], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                        <?= Html::a('Add Member', ['/member/create'], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                        <?= Html::a('Issue Book', ['/book-loan/create'], ['class' => 'btn btn-primary btn-sm']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100">
                <div class="card-body">
                    <h3 class="h6 fw-bold mb-2">About this project</h3>
                    <p class="small text-body-secondary mb-0">
                        This is a basic Yii2 Library Management System scaffold covering the MVC pattern,
                        ActiveRecord, migrations, GridView/DetailView and forms. See
                        <code>TASKS.md</code> in the project root for a walkthrough of the architecture.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
