<?php
$this->breadcrumbs=array(
	'Accounts'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Accounts','url'=>array('index')),
	array('label'=>'Create Account','url'=>array('create')),
);
?>

<h1>Update <?php echo $model->name; ?> Account</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>