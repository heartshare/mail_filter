<?php
$this->breadcrumbs=array(
	'Quiet Hours'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Intervals','url'=>array('index')),
);
?>

<h1>Add a Quiet Hour Interval</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>