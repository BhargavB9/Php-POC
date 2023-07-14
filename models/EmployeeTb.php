<?php

namespace app\models;

use yii\db\ActiveRecord;

class EmployeeTb extends ActiveRecord
{
    
    public static function tableName()
    {
        return 'EmployeeTb';
    }

    public function attributeLabels()
    {
        return [
            'Name' => 'Name',
            'EmployeeId' => 'Employee ID',
            'EmailId' => 'Email ID',
            'PhoneNo' => 'Phone Number',
        ];
    }
    
    public function rules()
    {
        return [
            [['Name', 'EmployeeId', 'EmailId', 'PhoneNo'], 'required'],
            [['EmployeeId', 'PhoneNo'], 'integer'],
            [['Name', 'EmailId'], 'string', 'max' => 50],
        ];
    }
}
