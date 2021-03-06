<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use andahrm\leave\models\LeaveCondition;
/* @var $this yii\web\View */
/* @var $model andahrm\leave\models\LeaveCondition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="leave-condition-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <div class="row">
      <div class="col-sm-3">
        
     
    <?= $form->field($model, 'gov_service_status')->radioList(LeaveCondition::getItemGovStatus()) ?>
      </div>
<div class="col-sm-9">
    <?= $form->field($model, 'number_year')->textInput() ?>
 </div>
  </div>
    <?= $form->field($model, 'per_annual_leave')->textInput() ?>

    <?= $form->field($model, 'per_annual_leave_limit')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('andahrm', 'Create') : Yii::t('andahrm', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
