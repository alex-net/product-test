<?php 

use yii\bootstrap4\Html;

$this->title = 'Товары';

echo Html::beginTag('div', ['class' => 'controls-wrapper']);
echo \yii\widgets\LinkSorter::widget([
	'sort' => $dp->sort,
	'options' => ['class' => 'sorter-wrapper'],
]);
echo \app\widgets\CatViewWidget::widget(['showAll' => true, 'aslink' => true, 'resetBtn' => true]);
echo Html::endTag('div');

echo \yii\widgets\ListView::widget([
	'dataProvider' => $dp,
	'summary' => false,
	'options' => ['class' => 'product-list-wrapper'],
	'itemView' => 'prod-item',
	'itemOptions' => ['class' => 'item'],
]);