<?php
$this->breadcrumbs=array(
	'Messages'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Message','url'=>array('index')),
	array('label'=>'View Message','url'=>array('view','id'=>$model->id)),
);
?>

<h1>Update Message <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>