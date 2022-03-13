<?php 

namespace app\modules\admin;

class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
	public function bootstrap($app)
	{
		$app->urlManager->addRules([
			'admin/cats'=>'/'.$this->id.'/cat/list',
            'admin/cats/<id:\d+>'=>'/'.$this->id.'/cat/edit',
            'admin/cats/new'=>'/'.$this->id.'/cat/edit',
            'admin/products'=>'/'.$this->id.'/product/list',
            'admin/products/<id:\d+>/edit'=>'/'.$this->id.'/product/edit',
            'admin/products/<id:\d+>'=>'/'.$this->id.'/product/view',
            'admin/products/new'=>'/'.$this->id.'/product/edit',
		]);
	}

	public function generateMenu()
	{
		return [
            ['label'=>'Категории','url'=>['/'.$this->id.'/cat/list']],
            ['label'=>'Товары','url'=>['/'.$this->id.'/product/list']],
        ];
	}
}