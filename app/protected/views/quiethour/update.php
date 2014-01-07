<?php
$this->breadcrumbs=array(
	'Quiet Hours'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List intervals','url'=>array('index')),
	array('label'=>'Add interval','url'=>array('create')),
);
?>

<h1>Update QuietHour Interval</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>