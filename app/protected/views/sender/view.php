<?php
$this->breadcrumbs=array(
	'Senders'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Sender','url'=>array('index')),
	array('label'=>'Create Sender','url'=>array('create')),
	array('label'=>'Update Sender','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Sender','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Sender','url'=>array('admin')),
);
?>

<h1>View Sender #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'account_id',
		'user_id',
		'personal',
		'email',
		'mailbox',
		'host',
		'is_verified',
		'folder_id',
		'last_trained',
		'created_at',
		'modified_at',
	),
)); ?>
