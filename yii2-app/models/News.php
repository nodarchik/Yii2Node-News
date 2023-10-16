<?php

namespace app\models;

use yii\base\Model;

class News extends Model
{
    public $id;  // Assuming there's an ID field in your MongoDB document
    public $title;
    public $content;

    /**
     * @return array the validation rules.
     */
    public function rules(): array
    {
        return [
            [['title', 'content'], 'required'],
            ['title', 'string', 'min' => 5, 'max' => 255],
            ['content', 'string', 'min' => 10],
        ];
    }

    /**
     * Populates the model attributes with the provided data.
     *
     * @param array $data
     */
    public function loadData(array $data)
    {
        foreach ($data as $key => $value) {
            if ($this->hasProperty($key)) {
                $this->$key = $value;
            }
        }
    }
}
