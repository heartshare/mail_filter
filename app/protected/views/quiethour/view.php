<?php
$this->breadcrumbs=array(
	'Quiet Hours'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List QuietHour','url'=>array('index')),
	array('label'=>'Create QuietHour','url'=>array('create')),
	array('label'=>'Update QuietHour','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete QuietHour','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage QuietHour','url'=>array('admin')),
);
?>

<h1>View QuietHour #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'days',
		'start_interval',
		'end_interval',
		'created_at',
		'modified_at',
	),
)); ?>
