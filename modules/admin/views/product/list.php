<?php 

use \yii\helpers\Html;

$this->title='Список товаров';

echo Html::a('Добавить',['edit'],['class'=>'btn btn-primary']);

echo \yii\grid\GridView::widget([
	'dataProvider'=>$dp,
	'columns'=>[
		'id:text:№',
		[
			'attribute'=>'name',
			'label'=>'Наименование',
			'format'=>'html',
			'value'=>function($m){
				return Html::a($m['name'],['view','id'=>$m['id']]);
			}
		],
		'price:integer:Цена',
		'status:boolean:Активно',
	],
]);