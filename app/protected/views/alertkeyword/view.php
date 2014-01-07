<?php
$this->breadcrumbs=array(
	'Alert Keywords'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List AlertKeyword','url'=>array('index')),
	array('label'=>'Create AlertKeyword','url'=>array('create')),
	array('label'=>'Update AlertKeyword','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete AlertKeyword','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage AlertKeyword','url'=>array('admin')),
);
?>

<h1>View AlertKeyword #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'keyword',
		'created_at',
		'modified_at',
	),
)); ?>
