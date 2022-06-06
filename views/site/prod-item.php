<?php 
$url = ['site/product-view', 'key' => $model['key']];
use yii\bootstrap4\Html;
?>
<div class="title"><?= Html::a($model['name'], $url) ?></div> 
<?= \app\widgets\CatViewWidget::widget(['cats' => $model['cats']])?>
<div class="price">Стоимость: <?= $model['price'] ?></div>
<div class="img"><?= Html::a(Html::img($model['img']), $url) ?></div>

