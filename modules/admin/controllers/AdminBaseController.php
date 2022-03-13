<?php

namespace app\modules\admin\controllers;

use Yii;

class AdminBaseController extends \yii\web\Controller
{
	public function behaviors()
	{
		return [
			[
				'class'=>\yii\filters\AccessControl::class,
				'rules'=>[
					['allow'=>true,'roles'=>['@']],
				],
			]
		];
	}

	protected function postOperations($obj,$saveMess,$killMess)
	{
		if (!Yii::$app->request->isPost)
			return ;

		$post=Yii::$app->request->post();
		switch(true){
			// сохранение ... 
			case isset($post['save']) && $obj->save($post):
				Yii::$app->session->addFlash('success',$saveMess);
				return $this->redirect($this->id=='cat'?['list']:['view','id'=>$obj->id]);
			// удаление ...
			case isset($post['kill']) && $obj->kill():
				Yii::$app->session->addFlash('info',sprintf($killMess,$obj->name));
				return $this->redirect(['list']);
		}
		return;
		
	}
}