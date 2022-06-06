<?php 
use  \yii\bootstrap4\ActiveForm;
use \yii\bootstrap4\ButtonGroup;
use \yii\bootstrap4\Html;
$this->params['breadcrumbs'][] = ['label' => 'Все категории', 'url' => ['list']];

$this->title = $cat->id ? 'Редактирование категории "' . $cat->name . '"' : 'Новая категория';
$this->params['breadcrumbs'][] = $this->title;

$f = ActiveForm::begin();
echo $f->field($cat, 'name');
echo $f->field($cat, 'color')->input('color');

$btn = [
    Html::submitButton('Сохраненить', ['class' => 'btn btn-primary', 'name' => 'save']),
];
if($cat->id) {
    $btn[] = Html::submitButton('Удалить', ['class' => 'btn btn-danger', 'name' => 'kill']);
}

echo ButtonGroup::widget([
    'buttons' => $btn,
]);
ActiveForm::end();