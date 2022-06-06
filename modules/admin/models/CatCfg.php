<?php

namespace app\modules\admin\models;

use Yii;

class CatCfg extends \app\models\Cat
{
    public function rules()
    {
        return [
            ['name', 'string', 'max' => 20],
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'checkExists'],
            ['color', 'match', 'pattern' => '#^\#[0-9,a-f]{6}$#i'],
            ['color', 'default', 'value' => '#000000'],
        ];
    }

    public function checkExists($attr)
    {
        $where = ['and', ['=', 'name', $this->$attr]];
        if ($this->id) {
            $where[] = ['not', ['=', 'id', $this->id]];
        }

        $q = new \yii\db\Query();
        $q->from('cats');
        $q->where($where);
        if ($q->count()) {
            $this->addError($attr, 'Категория уже есть в базе');
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Наименование',
            'color' => 'Цвет',
        ];
    }

    public static function getAll()
    {
        return new \yii\data\SqlDataProvider([
            'sql' => 'select * from cats',
        ]);
    }

    public function save($data):bool
    {
        if ($data && !$this->load($data) || !$this->validate()) {
            return false;
        }
        $args = $this->getAttributes($this->activeAttributes());
        $args['color'] = substr($args['color'], 1);
        if ($this->id) {
            Yii::$app->db->createCommand()->update('cats', $args, ['id' => $this->id])->execute();
        } else {
            Yii::$app->db->createCommand()->insert('cats', $args)->execute();
        }
        return true;
    }

    public function kill(): bool
    {   
        if (!$this->id) {
            return false;
        }
        Yii::$app->db->createCommand()->delete('cats', ['id' => $this->id])->execute();
        return true;
    }

}