<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Edit Employee';
$this->params['breadcrumbs'][] = ['label' => 'Employee Details', 'url' => ['employeedetails']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-edit">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($employee, 'Name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($employee, 'EmployeeId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($employee, 'EmailId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($employee, 'PhoneNo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
