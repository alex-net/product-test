<h1><?= $p->name ?></h1>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-6 price">Стоимость: <?= $p->price ?></div>
		<div class="col-lg-6 cats"><?= \app\widgets\CatViewWidget::widget(['cats' => $p->cats])?></div>
	</div>
	<div class="row">
		<div class="col-lg-6"><?= $p->descr ?></div>
		<div class="col-lg-6"><?= \powerkernel\photoswipe\Gallery::widget([
			'items' => $p->imgForGalery,
			]) ?></div>
	</div>
</div>