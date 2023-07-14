<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public static function tableName()
    {
        return 'Admin';
    }
    public $username;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'unique', 'targetClass' => 'app\models\Admin', 'targetAttribute' => 'username'],
        ];
    }

    
    public function register()
    {

        if ($this->validate()) {
            $admin = new Admin();
            $admin->Username = $this->username;
            $admin->Password = $this->password;
            var_dump($admin); exit();
            
            if ($admin->save()) {
                var_dump($admin);
                echo"inside"; exit();
                return true;
            }else{
                echo"outside"; 
                print_r($admin->getAttributes());
                print_r($admin->getErrors());
                
                exit();
            }
        }
        return false;
    }
    
}
