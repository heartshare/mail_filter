<?php
$this->breadcrumbs=array(
	'Folders'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Folder','url'=>array('index')),
	array('label'=>'Create Folder','url'=>array('create')),
	array('label'=>'Update Folder','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Folder','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Folder','url'=>array('admin')),
);
?>

<h1>View Folder #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'account_id',
		'user_id',
		'name',
		'train',
		'digest',
		'created_at',
		'modified_at',
	),
)); ?>
