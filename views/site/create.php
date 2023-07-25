<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Add Employee';
$this->params['breadcrumbs'][] = ['label' => 'Employee Details', 'url' => ['employeedetails']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($employee, 'Name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($employee, 'EmployeeId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($employee, 'EmailId')->textInput(['maxlength' => true]) ?>

    <?= $form->field($employee, 'PhoneNo')->textInput(['maxlength' => true]) ?>

    <!-- Image upload field -->
    <?= $form->field($employee, 'imageFile')->fileInput() ?>

    <!-- Document upload field -->
    <?= $form->field($employee, 'documentFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Add', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
