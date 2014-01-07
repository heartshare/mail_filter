<?php
$this->breadcrumbs=array(
	'Recipients'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Recipients','url'=>array('index')),
);
?>

<h1>Update Recipient</h1>

<?php echo $this->renderPartial('_form',array('model'=>$model,'messages'=>$messages)); ?>