<?php
$this->breadcrumbs=array(
	'Senders'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Sender','url'=>array('index')),
	array('label'=>'Manage Sender','url'=>array('admin')),
);
?>

<h1>Create Sender</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>