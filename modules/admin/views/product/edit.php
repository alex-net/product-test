<?php 

use yii\bootstrap4\ActiveForm;
use \yii\bootstrap4\ButtonGroup;
use \yii\bootstrap4\Html;

$this->title='Новый товар';

if ($prod->id)
	$this->title='Редактирование товара';

$this->params['breadcrumbs'][]=['label'=>'Все товары','url'=>['list']];
if ($prod->id)
	$this->params['breadcrumbs'][]=['label'=>'Просмотр товара','url'=>['view','id'=>$prod->id]];
$this->params['breadcrumbs'][]=$this->title;

$f=ActiveForm::begin();

echo $f->field($prod,'name');
echo $f->field($prod,'price');
echo $f->field($prod,'status')->checkbox();
echo $f->field($prod,'descr')->widget(mihaildev\ckeditor\CKEditor::class,['editorOptions'=>[
	'preset'=>'basic',
]]);

echo $f->field($prod,'cats')->checkboxList(\app\models\Cat::getAllIds());

$btn=[
	Html::submitButton('Сохраненить',['class'=>'btn btn-primary','name'=>'save']),
];

if($prod->id)
	$btn[]=Html::submitButton('Удалить',['class'=>'btn btn-danger','name'=>'kill']);

echo ButtonGroup::widget([
	'buttons'=>$btn,
]);

ActiveForm::end();