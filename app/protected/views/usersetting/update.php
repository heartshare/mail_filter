<?php
$this->breadcrumbs=array(
	'User Settings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
/*
$this->menu=array(
	array('label'=>'List UserSetting','url'=>array('index')),
	array('label'=>'Create UserSetting','url'=>array('create')),
	array('label'=>'View UserSetting','url'=>array('view','id'=>$model->id)),
	array('label'=>'Manage UserSetting','url'=>array('admin')),
);
*/
?>
 <div class="row digestOn">
<h1>Update Your Settings</h1>
</div>
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>