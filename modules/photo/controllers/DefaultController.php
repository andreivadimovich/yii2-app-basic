<?php

namespace app\modules\photo\controllers;

use Yii;
use app\modules\photo\models\Photo;
use app\modules\photo\models\PhotoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\modules\photo\models\UploadForm;
use yii\web\UploadedFile;

use yii\filters\AccessControl;

/**
 * PhotoController implements the CRUD actions for Photo model.
 */
class DefaultController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],

            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

    /**
     * Upload multiple photos.
     * @return mixed
     */
    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            $images = Photo::buildPhotoCollection($model);

            if ($model->upload()) {
                Yii::$app->db->createCommand()->batchInsert('photo', ['title', 'user_id', 'img_path', 'img_hash'],
                    $images)->execute();

                Yii::$app->session->setFlash('success', "Selected photos uploaded successfully!
                    Now you can fill out their description");
                return $this->redirect(['index']);

            } else {
                Yii::$app->session->setFlash('error', "Error loading files. Please upload files again!");
            }
        }

        return $this->render('upload', [
            'model' => $model
        ]);
    }

    /**
     * Lists all Photo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PhotoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Photo model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Photo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Photo model.
     * If deletion is successful and not multiple, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param bool $multiple
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $multiple = false)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {

            // Checking the existence of a file with another user
            if (Photo::find()->where(['img_hash' => $model->img_hash])->count() == 0) {
                @unlink(Yii::$app->basePath . '/web/' . UploadForm::IMAGE_PATH . $model->img_path);
            }
        }

        if ($multiple === true) {
            return true;
        }

        return $this->redirect(['index']);
    }

    /**
     * Deletes multiple an existing Photo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteMultiple()
    {
        $post = Yii::$app->request->post();

        if (isset($post['selection']) && !empty($post['selection'])) {
            foreach ($post['selection'] as $id) {
                $this->actionDelete($id, true);
            }

            Yii::$app->session->setFlash('sucess', "Selected photos deleted");
        } else {
            Yii::$app->session->setFlash('info', "You have not selected more than one photo to delete");
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Photo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Photo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Photo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}