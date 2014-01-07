<?php
$this->breadcrumbs=array(
	'Messages',
);
/*
$this->menu=array(
	array('label'=>'Manage Message','url'=>array('admin')),
);*/

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('message-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

?>

<h1>Messages</h1>
<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'message-grid',
	'type' => 'striped bordered',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
	      array(            
                    'name'=>'email',
                    'header'=>'From',
                    'value'=>'$data->email',
                ),
        'subject',
    array(
        'name'=>'udate',
            'header' => 'Received',
             'value' => 'message_udate($data->udate)',
        ),
	      array(            
                    'name'=>'folder_name',
                    'header'=>'Folder',
                    'filter'=>CHtml::listData(Folder::model()->findAll(),'name','name'),
                    'filter'=>CHtml::dropDownList(
                                                    'Message[folder_name]',
                                                    $model->folder_name,
                                                    CHtml::listData(
                                                            Folder::model()->findAll(),
                                                            'name',
                                                            'name'),array('empty' => 'All')),
                    'value'=>'$data->folder_name',
                ),
          	        array(
              'htmlOptions'=>array('width'=>'100px'),  		
            	'class'=>'bootstrap.widgets.TbButtonColumn',
            	'header'=>'Options',
              'template'=>'{view}', // '{delete}'
                  'buttons'=>array
                  (
                      'delete' => array
                      (
                      'options'=>array('title'=>'trash'),
                        'label'=>'<i class="icon-trash icon-large" style="margin:5px;"></i>',
                        'url'=>'Yii::app()->createUrl("message/delete", array("id"=>$data->id))',
                      ),
                  ),			
            ),
	),
)); ?>

