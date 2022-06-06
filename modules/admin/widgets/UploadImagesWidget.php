<?php 

namespace app\modules\admin\widgets;

class UploadImagesWidget extends \yii\base\Widget
{
	public $forProd;

	public function run()
	{
		\app\modules\admin\assets\UploadImagesWidgetAsset::register($this->view);
		return $this->render('image-upload', [
			'id' => $this->forProd,
		]);
		//\yii\bootstrap4\Html::tag('div','',['class'=>'image-upload-wrapper']);		 
	}
}