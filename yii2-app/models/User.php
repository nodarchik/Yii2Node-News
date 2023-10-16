<?php

namespace app\models;

use yii\base\Model;

class User extends Model
{
    public $username;
    public $password;

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'string', 'min' => 5],
            ['password', 'string', 'min' => 8],
        ];
    }
}
