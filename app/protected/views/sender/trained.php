<?php
$this->breadcrumbs=array(
	'Senders',
);
$this->menu=array(
//	array('label'=>'Create Sender','url'=>array('create')),
 array('label'=>'Manage Sender','url'=>array('admin')),
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('sender-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

?>
<h1>Senders</h1>
<div>
  <div class="right">
    </div>
  <div class="left;">
  <?php
  $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'success',
        'toggle' => 'radio',
            'buttons'=>array(
              array('label'=>'Recent', 'url'=>Yii::app()->getBaseUrl(true).'/sender/recent'),            
                array('label'=>'Untrained', 'url'=>Yii::app()->getBaseUrl(true).'/sender/index'),
                array('label'=>'Trained', 'url'=>Yii::app()->getBaseUrl(true).'/sender/trained','htmlOptions'=> array('class'=>'active')),
      ),
  ));
  ?>
</div>
</div>

<?php 
$this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'sender-grid',
	'dataProvider'=>$model->trained()->search(),
	'filter'=>$model,
	'columns'=>array(
		'personal',
		'email',
  	array('class'=>'CDataColumn','name'=> 'message_count', 'header'=>'# Msgs'),
    array(            
                'name'=>'folder_name',
                'header'=>'Folder',
                'filter'=>CHtml::dropDownList(
                                                'Sender[folder_name]',
                                                $model->folder_name,
                                                CHtml::listData(
                                                        Folder::model()->findAll(),
                                                        'name',
                                                        'name'),array('empty' => 'All')),
                'value'=>'$data->folder_name',
            ),
    array(            
                'name'=>'account_name',
                'header'=>'Account',
                'filter'=>CHtml::dropDownList(
                                                'Sender[account_name]',
                                                $model->account_name,
                                                CHtml::listData(
                                                        Account::model()->findAll(),
                                                        'name',
                                                        'name'),array('empty' => 'All')),                
                'value'=>'$data->account_name',
            ),
		array(
			'class'=>'bootstrap.widgets.TbButtonColumn',
    	'header'=>'Options',
      'template'=>'{update}{delete}',
		),
	),
)); ?>