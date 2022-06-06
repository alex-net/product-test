<?php 

use \yii\bootstrap4\ButtonGroup; 
use \yii\bootstrap4\Html;

$this->title = 'Просмотр товара "' . $prod->name . '"';

$this->params['breadcrumbs'][] = ['label' => 'Все товары', 'url' => ['list']];
$this->params['breadcrumbs'][] = $this->title;


\yii\bootstrap4\ActiveForm::begin();
echo ButtonGroup::widget([
    'buttons' => [
        Html::a('Редактировать', ['edit', 'id' => $prod->id], ['class' => 'btn btn-primary', 'name' => 'kill']),
        Html::submitButton('Удалить', ['class' => 'btn btn-danger', 'name' => 'kill']),
    ],
]);
\yii\bootstrap4\ActiveForm::end();

echo \yii\widgets\DetailView::widget([
    'model' => $prod,
    'attributes' => [
        'name',
        'status:boolean',
        'price:integer',
        'descr',
        [
            'attribute' => 'cats',
            'format' => 'raw',
            'value' => function($m) {
                return \app\widgets\CatViewWidget::widget([
                    'cats' => $m->cats,
                ]);
            },
        ],
    ],
]);

echo \app\modules\admin\widgets\UploadImagesWidget::widget(['forProd' => $prod->id]);