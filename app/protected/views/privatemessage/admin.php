<?php
$this->breadcrumbs=array(
	'Private Messages'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List PrivateMessage','url'=>array('index')),
	array('label'=>'Create PrivateMessage','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('private-message-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Private Messages</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button btn')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'private-message-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'user_id',
		'account_id',
		'folder_id',
		'sender_id',
		'message_id',
		/*
		'subject',
		'body_text',
		'body_html',
		'udate',
		'status',
		'created_at',
		'modified_at',
		*/
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
		),
	),
)); ?>
