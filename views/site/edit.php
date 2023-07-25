<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Edit Employee';
$this->params['breadcrumbs'][] = ['label' => 'Employee Details', 'url' => ['employeedetails']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-edit">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($employee, 'Name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($employee, 'EmployeeId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($employee, 'EmailId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($employee, 'PhoneNo')->textInput(['maxlength' => true]) ?>

    <!-- Existing image file -->
    <div class="form-group">
        <label>Existing Image:</label>
        <img src="<?= $employee->image_path ?>" alt="Employee Image" width="150">
    </div>

    <?= $form->field($employee, 'imageFile')->fileInput() ?>

    <!-- Existing document file -->
    <div class="form-group">
        <label>Existing Document:</label>
        <a href="<?= $employee->document_path ?>" target="_blank">View Document</a>
    </div>

    <?= $form->field($employee, 'documentFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
