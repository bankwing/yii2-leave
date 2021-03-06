<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel andahrm\leave\models\LeaveCancelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('andahrm/leave', 'Leave Cancels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-cancel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('andahrm/leave', 'Create Leave Cancel'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'to',
            'leave_id',
            'reason',
            'date_start',
            // 'start_part',
            // 'date_end',
            // 'end_part',
            // 'number_day',
            // 'status',
            // 'commander_comment:ntext',
            // 'commander_status',
            // 'commander_by',
            // 'commander_at',
            // 'director_comment',
            // 'director_status',
            // 'director_by',
            // 'director_at',
            // 'created_at',
            // 'created_by',
            // 'updated_at',
            // 'updated_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
