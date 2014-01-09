<?php
$this->breadcrumbs=array(
	'Private Messages'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List PrivateMessage','url'=>array('index')),
	array('label'=>'Create PrivateMessage','url'=>array('create')),
	array('label'=>'View PrivateMessage','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage PrivateMessage','url'=>array('admin')),
);
?>

<h1>Update PrivateMessage <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>