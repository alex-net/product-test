<?php 

namespace app\models;

use Yii;

class Cat extends \yii\base\Model
{
    public $id, $name, $color;

    public function init()
    {
        parent::init();
        if ($this->color && strpos($this->color, '#') !== 0) {
            $this->color = '#' . $this->color;
        }
    }

    public static function getAllIds()
    {
        $res = Yii::$app->db->createCommand('select id,name from cats')->queryAll();
        $list = [];
        for($i = 0; $i < count($res); $i++) {
            $list[$res[$i]['id']] = $res[$i]['name'];
        }
        return $list;
    }

    /**
     * получение объектов категорий .. 
     * @param  array|int $id номер категорий .. .
     * @return [type]     [description]
     */
    public static function getById($id)
    {
        $q=new \yii\db\Query;
        $q->from('cats');
        $q->where(['id' => $id]);
        $q = $q->all();
        for($i = 0; $i < count($q); $i++) {
            $q[$i] = new static($q[$i]);
        }

        if (!is_array($id)) {
            $q = reset($q);
        }

        return $q;
    }
}