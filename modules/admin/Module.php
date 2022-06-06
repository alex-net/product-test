<?php 

namespace app\modules\admin;

class Module extends \yii\base\Module implements \yii\base\BootstrapInterface
{
    public function bootstrap($app)
    {
        $id = '/' . $this->id;
        $app->urlManager->addRules([
            'admin/cats' => $id . '/cat/list',
            'admin/cats/<id:\d+>' => $id . '/cat/edit',
            'admin/cats/new' => $id . '/cat/edit',
            'admin/products' => $id . '/product/list',
            'admin/products/<id:\d+>/edit' => $id . '/product/edit',
            'admin/products/<id:\d+>' => $id . '/product/view',
            'admin/products/new' => $id . '/product/edit',
        ]);
    }

    public function generateMenu()
    {
        return [
            ['label' => 'Категории', 'url' => ['/' . $this->id . '/cat/list']],
            ['label' => 'Товары', 'url' => ['/' . $this->id . '/product/list']],
        ];
    }
}