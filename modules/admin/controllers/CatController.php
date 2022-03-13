<?php 

namespace app\modules\admin\controllers;

class CatController extends AdminBaseController
{
	/**
	 * Список категорий .. 
	 * @return [type] [description]
	 */
	public function actionList()
	{
		return $this->render('list',[
			'dp'=>\app\modules\admin\models\CatCfg::getAll(),
		]);
	}
	/**
	 * редактирование категории ....
	 * @param  integer $id [description]
	 * @return [type]      [description]
	 */
	public function actionEdit($id=0)
	{
		$cat=$id?\app\modules\admin\models\CatCfg::getById($id):new \app\modules\admin\models\CatCfg;
		if (!$cat)
			throw new \yii\web\NotFoundHttpException("Категория отсутствует");
		
		$ret=$this->postOperations($cat,'Данные сохранены','Категория %s удалена');
		if ($ret)
			return $ret;

		return $this->render('edit',['cat'=>$cat]);
	}
}