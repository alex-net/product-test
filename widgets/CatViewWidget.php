<?php 

namespace app\widgets;

use Yii;
use yii\bootstrap4\Html;

class CatViewWidget extends \yii\base\Widget
{
	public $cats=[];// набор отображжаемых категорий ... 
	public $showAll=false; // загрузка всех категорий .. 
	public $aslink; // отображать списков в виде ссылок ... нужно анонимну. функцию которая будет возмращать роуты .. 
	public $resetBtn; // добавить кнопку сброса ..
	public function run()
	{
		if ($this->showAll)
			$this->cats=array_keys(\app\models\Cat::getAllIds());
		
		if (!$this->cats)
			return;
		$cats=\app\models\Cat::getById($this->cats);
		for($i=0;$i<count($cats);$i++){
			$text=Html::tag('span',$cats[$i]->name,['class'=>'cat','style'=>'background:'.$cats[$i]->color]);
			if ($this->aslink)
				$text=Html::a($text,['','cat'=>$cats[$i]->id]);
			$cats[$i]=$text;
		}
		if ($this->resetBtn && $this->aslink )
			array_unshift($cats, Html::a('<span class="cat">Сбррос</span>',['']));

		return Html::tag('div',implode('',$cats),['class'=>'cats-list']);
	}
}
