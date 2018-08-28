<?php

namespace andahrm\leave\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\web\Response;
###
use andahrm\leave\models\PersonSearch;
use andahrm\leave\models\LeavePermission;
use andahrm\leave\models\LeavePermissionSearch;
use andahrm\leave\models\LeavePermissionTransection;
use andahrm\leave\models\PersonLeave;
use andahrm\leave\models\PersonPermissionSearch;
use andahrm\structure\models\FiscalYear;

/**
 * PermissionController implements the CRUD actions for LeavePermission model.
 */
class PermissionController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all LeaveDayOff models.
     * @return mixed
     */
    public function actions() {
        $this->layout = 'menu-left-setting';
    }

    /**
     * Lists all LeavePermission models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new PersonPermissionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LeavePermission model.
     * @param integer $user_id
     * @param integer $leave_condition_id
     * @param string $year
     * @return mixed
     */
    public function actionView($user_id, $leave_condition_id, $year) {
        return $this->render('view', [
                    'model' => $this->findModel($user_id, $leave_condition_id, $year),
        ]);
    }

    public function actionManage($id, $year = null) {
        $year = $year == null ? FiscalYear::currentYear() : $year;
        $options = ['user_id' => $id, 'year' => $year];
        $model = LeavePermission::findOne($options);
        $model = $model ? $model : new LeavePermission($options);
        $modelPerson = PersonLeave::findOne($id);

        $modelTrans = LeavePermissionTransection::findAll($options);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $modelTrans
        ]);

        return $this->render('manage', [
                    'model' => $model,
                    'modelPerson' => $modelPerson,
                    'dataProvider' => $dataProvider
        ]);
    }

    public function actionAssign($id, $year) {
        $modelPerson = PersonLeave::findOne($id);
        $model = new LeavePermissionTransection(['user_id' => $id, 'year' => $year]);

//        $modelTrans = LeavePermissionTransection::findAll(['user_id' => $modelPerson->user_id]);
//        $dataProvider = new ArrayDataProvider([
//            'allModels' => $modelTrans
//        ]);
        $request = Yii::$app->request;
        $post = $request->post();
        $isAjax = Yii::$app->request->isAjax;
        //echo $isAjax;
        if ($model->load($post)) {
            $success = false;
            $result = null;

            if ($isAjax && $request->post('ajax')) {
                return ActiveForm::validate($model);
            } else {
//                print_r($post);
//                exit();
                $model->trans_time = time();
                $model->trans_type = LeavePermissionTransection::TYPE_ADD;
                //$model->trans_by = Yii::$app->user->identity->id;
                if ($model->save()) {
                    $success = true;
                } else {
                    $result = $model->getErrors();
                    print_r($result);
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => $success, 'result' => $result];
            }
        }
        $param = [
            'model' => $model,
            'modelPerson' => $modelPerson,
            'modelTrans' => $modelTrans,
            'dataProvider' => $dataProvider,
            'isAjax' => $isAjax
        ];
        if ($isAjax) {
            return $this->renderAjax('assign', $param);
        } else {
            return $this->render('assign', $param);
        }
    }

    public function actionMinus($id, $year) {
        $modelPerson = PersonLeave::findOne($id);
        $model = new LeavePermissionTransection(['user_id' => $id, 'year' => $year]);

        $request = Yii::$app->request;
        $post = $request->post();
        $isAjax = Yii::$app->request->isAjax;
        //echo $isAjax;
        if ($model->load($post)) {
            $success = false;
            $result = null;

            if ($isAjax && $request->post('ajax')) {
                return ActiveForm::validate($model);
            } else {
                $model->trans_time = time();
                $model->trans_type = LeavePermissionTransection::TYPE_MINUS;
                if ($model->save()) {
                    $success = true;
                } else {
                    $result = $model->getErrors();
                    print_r($result);
                }
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => $success, 'result' => $result];
            }
        }
        $param = [
            'model' => $model,
            'modelPerson' => $modelPerson,
            'modelTrans' => $modelTrans,
            'dataProvider' => $dataProvider,
            'isAjax' => $isAjax
        ];
        if ($isAjax) {
            return $this->renderAjax('minus', $param);
        } else {
            return $this->render('minus', $param);
        }
    }

    public function actionDel($id, $time) {

        $model = LeavePermissionTransection::findOne(['user_id' => $id, 'trans_time' => $time]);
        $yaer = $model->year;
        if ($model) {
            $model->delete();
            LeavePermission::updateBalance($id);
        }
        return $this->redirect(['manage', 'id' => $id, 'year' => $year]);
    }

    public function actionUpdateBalance($id, $year) {
        LeavePermission::updateBalance($id, $year);
        return $this->redirect(['manage', 'id' => $id, 'year' => $year]);
    }

    public function actionUpdateCarry($id, $year) {
        LeavePermissionTransection::getLastBalanceYear($id, $year);
        return $this->redirect(['manage', 'id' => $id, 'year' => $year]);
    }

    /**
     * Creates a new LeavePermission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new LeavePermission(['scenario' => 'create']);

        $request = Yii::$app->request;
        $post = $request->post();

        if ($model->load($post) && $request->post('save')) {

            $postLeave = $post['LeavePermission'];
            echo "<pre>";
            print_r($post);
            exit();
            $flag = true;

            foreach ($postLeave['user_id'] as $key => $item) {
                $newModel = [];

                if ($newModel = LeavePermission::find()->where(['year' => $model->year, 'user_id' => $item])->one()) {
                    $newModel->number_day = $post['number_day'][$key];
                } else {
                    $newModel = new LeavePermission(['scenario' => 'insert']);
                    $newModel->user_id = $item;
                    $newModel->year = $model->year;
                    $newModel->number_day = $post['number_day'][$key];
                }
                if (!$newModel->save()) {
                    $flag = false;
                }
            }


            if ($flag)
                return $this->redirect(['index']);
        }

        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('create', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing LeavePermission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param integer $leave_condition_id
     * @param string $year
     * @return mixed
     */
    public function actionUpdate($user_id, $leave_condition_id, $year) {
        $model = $this->findModel($user_id, $leave_condition_id, $year);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'leave_condition_id' => $model->leave_condition_id, 'year' => $model->year]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing LeavePermission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param integer $leave_condition_id
     * @param string $year
     * @return mixed
     */
    public function actionDelete($user_id, $leave_condition_id, $year) {
        $this->findModel($user_id, $leave_condition_id, $year)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LeavePermission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @param integer $leave_condition_id
     * @param string $year
     * @return LeavePermission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $leave_condition_id, $year) {
        if (($model = LeavePermission::findOne(['user_id' => $user_id, 'leave_condition_id' => $leave_condition_id, 'year' => $year])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
