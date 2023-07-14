<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Employee Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-employeedetails">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('Add Employee', ['create'], ['class' => 'btn btn-success']) ?>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Employee Id</th>
                <th>Email Id</th>
                <th>Phone Number</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($employees as $employee): ?>
                <tr>
                    <td><?= Html::encode($employee->Name) ?></td>
                    <td><?= Html::encode($employee->EmployeeId) ?></td>
                    <td><?= Html::encode($employee->EmailId) ?></td>
                    <td><?= Html::encode($employee->PhoneNo) ?></td>
                    <td>
                        <?= Html::a('Edit', ['edit', 'EmployeeId' => $employee->EmployeeId], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'EmployeeId' => $employee->EmployeeId], ['class' => 'btn btn-danger', 'data' => ['confirm' => 'Are you sure to delete?', 'method' => 'post']]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


</div>
