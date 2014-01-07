<?php
$this->breadcrumbs=array(
	'Recipients'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Recipient','url'=>array('index')),
	array('label'=>'Create Recipient','url'=>array('create')),
	array('label'=>'Update Recipient','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Recipient','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Recipient','url'=>array('admin')),
);
?>

<h1>View Recipient #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'account_id',
		'user_id',
		'email',
		'folder_id',
		'created_at',
		'modified_at',
	),
)); ?>
