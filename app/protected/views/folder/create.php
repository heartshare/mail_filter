<?php
$this->breadcrumbs=array(
	'Folders'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Folder','url'=>array('index')),
	array('label'=>'Manage Folder','url'=>array('admin')),
);
?>

<h1>Create Folder</h1>

<?php echo $this->renderPartial('_new', array('model'=>$model)); ?>