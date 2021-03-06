<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model andahrm\leave\models\LeaveCancel */

$this->title = Yii::t('andahrm/leave', 'Create Leave Cancel');
$this->params['breadcrumbs'][] = ['label' => Yii::t('andahrm/leave', 'Leave Cancels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-cancel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
