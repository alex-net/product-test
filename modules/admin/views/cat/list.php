<?php 

use \yii\helpers\Html;

$this->title = 'Список категорий';

echo Html::a('Добавить', ['edit'], ['class' => 'btn btn-primary']);

echo \yii\grid\GridView::widget([
    'dataProvider' => $dp,
    'columns' => [
        [
            'label' => 'Наименовние',
            'attribute' => 'name',
            'format' => 'html',
            'value' => function($m) {
                return Html::a($m['name'], ['edit', 'id' => $m['id']]);
            },
        ],
        [
            'attribute' => 'color',
            'label' => 'Цвет',
            'contentOptions' => function($m) {
                return [
                    'style' => 'background-color:#' . $m['color'],
                ];
            },  
        ],
    ],
]);