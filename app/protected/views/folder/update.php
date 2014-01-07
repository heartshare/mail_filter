<?php
$this->breadcrumbs=array(
	'Folders'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Folders','url'=>array('index')),
	array('label'=>'Create Folder','url'=>array('create')),
);
?>

<h1>Update <?php echo $model->name; ?> Folder</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>