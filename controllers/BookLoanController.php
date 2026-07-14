<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Book;
use app\models\BookLoan;
use app\models\BookLoanSearch;
use app\models\Member;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BookLoanController implements the CRUD actions for BookLoan model,
 * plus the "issue book" / "return book" workflow of the library.
 */
class BookLoanController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'return' => ['POST'],
                ],
            ],
        ]);
    }

    /**
     * Lists all BookLoan models.
     */
    public function actionIndex()
    {
        $searchModel = new BookLoanSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BookLoan model.
     * @param int $id ID
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Issues a book to a member: creates a BookLoan record and
     * decrements the book's available copy count.
     */
    public function actionCreate()
    {
        $model = new BookLoan();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $book = Book::findOne($model->book_id);

            if ($book === null || $book->available_copies < 1) {
                Yii::$app->session->setFlash('error', 'Selected book has no available copies.');
            } else {
                $model->status = BookLoan::STATUS_BORROWED;

                $transaction = Yii::$app->db->beginTransaction();
                if ($model->save()) {
                    $book->available_copies -= 1;
                    if ($book->save(false)) {
                        $transaction->commit();
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                    $transaction->rollBack();
                } else {
                    $transaction->rollBack();
                }
            }
        } else {
            $model->borrow_date = date('Y-m-d');
            $model->due_date = date('Y-m-d', strtotime('+14 days'));
        }

        return $this->render('create', [
            'model' => $model,
            'books' => Book::find()->andWhere(['>', 'available_copies', 0])->orderBy('title')->all(),
            'members' => Member::find()->orderBy('full_name')->all(),
        ]);
    }

    /**
     * Marks a loan as returned and restocks the book copy.
     * @param int $id ID
     */
    public function actionReturn($id)
    {
        $model = $this->findModel($id);
        $model->markReturned();

        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing BookLoan model.
     * @param int $id ID
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BookLoan model based on its primary key value.
     * @param int $id ID
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id): BookLoan
    {
        if (($model = BookLoan::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested loan record does not exist.');
    }
}
