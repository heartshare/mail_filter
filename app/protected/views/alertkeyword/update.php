<?php
$this->breadcrumbs=array(
	'Alert Keywords'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List AlertKeyword','url'=>array('index')),
	array('label'=>'Create AlertKeyword','url'=>array('create')),
	array('label'=>'View AlertKeyword','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage AlertKeyword','url'=>array('admin')),
);
?>

<h1>Update AlertKeyword <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>