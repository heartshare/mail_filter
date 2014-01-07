<?php
$this->breadcrumbs=array(
	'Recipients'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Recipient','url'=>array('index')),
	array('label'=>'Manage Recipient','url'=>array('admin')),
);
?>

<h1>Create Recipient</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>