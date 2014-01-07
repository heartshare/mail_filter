<?php
$this->breadcrumbs=array(
	'Accounts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Accounts','url'=>array('index')),
);
?>

<h1>Create Account</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>