<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel andahrm\leave\models\LeaveRelatedPersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('andahrm/leave', 'Leave Related People');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-related-person-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('andahrm/leave', 'Create Leave Related Person'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
             [
                'attribute' => 'leave_related_id',
                'value' => function($model){
                return  $model->leaveRelated->title;
              }
            ],
            [
                'attribute' => 'user_id',
                'value' => function($model){
                return  $model->user->fullname;
              }
            ],           
  
            'created_at',
            'created_by',
            'updated_at',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
