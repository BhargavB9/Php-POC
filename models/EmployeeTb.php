<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * This is the model class for table "EmployeeTb".
 *
 * @property string $Name
 * @property int $EmployeeId
 * @property string $EmailId
 * @property int $PhoneNo
 * @property string $created
 * @property string $updated
 * @property string|null $last_loggedin_admin
 * @property string|null $login_time
 * @property string|null $created_by
 * @property string|null $created_on
 * @property string|null $edited_by
 * @property string|null $edited_on
 * @property string|null $image_path
 * @property string|null $document_path
 *
 * @property UploadedFile|null $imageFile
 */
class EmployeeTb extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile|null
     */
    public $imageFile;

     /**
     * @var UploadedFile|null
     */
    public $documentFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'EmployeeTb';
    }

    /**
     * @inheritdoc
     */
    public function rules()
{
    return [
        [['Name', 'EmployeeId', 'EmailId', 'PhoneNo'], 'required'],
        [['EmployeeId', 'PhoneNo'], 'integer'],
        [['created', 'updated', 'login_time', 'created_on', 'edited_on'], 'safe'],
        [['Name', 'EmailId'], 'string', 'max' => 50],
        [['last_loggedin_admin', 'created_by', 'edited_by', 'image_path', 'document_path'], 'string', 'max' => 255],
        [['EmployeeId'], 'unique'],
        [['imageFile'], 'file', 'extensions' => 'jpg', 'minSize' => 10240, 'maxSize' => 5242880],
        [['documentFile'], 'file', 'extensions' => 'pdf', 'minSize' => 10240, 'maxSize' => 5242880],
    ];
}



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Name' => 'Name',
            'EmployeeId' => 'Employee ID',
            'EmailId' => 'Email ID',
            'PhoneNo' => 'Phone No',
            'created' => 'Created',
            'updated' => 'Updated',
            'last_loggedin_admin' => 'Last Loggedin Admin',
            'login_time' => 'Login Time',
            'created_by' => 'Created By',
            'created_on' => 'Created On',
            'edited_by' => 'Edited By',
            'edited_on' => 'Edited On',
            'image_path' => 'Image Path',
            'document_path' => 'Document Path',
        ];
    }

    /**
     * Handles the file upload and saves the uploaded file.
     * @param string $attribute the attribute name
     * @return bool whether the file is uploaded successfully
     */
    public function upload($attribute)
    {
        if ($this->validate([$attribute])) {
            $filePath = 'uploads/images/' . Yii::$app->security->generateRandomString() . '.' . $this->$attribute->extension;
            if ($this->$attribute->saveAs($filePath)) {
                $this->$attribute = $filePath;
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created = date('Y-m-d H:i:s');
            }
            $this->updated = date('Y-m-d H:i:s');
            return true;
        }
        return false;
    }
}
