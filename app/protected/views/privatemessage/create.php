<?php
$this->breadcrumbs=array(
	'Private Messages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List PrivateMessage','url'=>array('index')),
	array('label'=>'Manage PrivateMessage','url'=>array('admin')),
);
?>

<h1>Create PrivateMessage</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>