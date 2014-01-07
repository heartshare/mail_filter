<?php
$this->breadcrumbs=array(
	'Recipients',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('recipient-grid', {
		data: $(this).serialize()
	});
	return false;
});
");

?>
<h1>Recipients</h1>
<?php 
  Yii::app()->user->setFlash('recipient_note','<p><em>For performance reasons, we collect the first address on the To: line - which may not be your mailbox address in every case. It is fine to delete any unrelated recipients.</em></p>');	    
  $this->widget('bootstrap.widgets.TbAlert', array(
      'alerts'=>array( // configurations per alert type
  	    'recipient_note'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
      ),
  ));
?>
<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'recipient-grid',
	'dataProvider'=>$model->active()->search(),
	'filter'=>$model,
	'columns'=>array(
		'email',
  	array('class'=>'CDataColumn','name'=> 'message_count', 'header'=>'# Msgs'),
  array(            
          'name'=>'folder_name',
          'header'=>'Folder',
          'filter'=>CHtml::dropDownList(
                                          'Recipient[folder_name]',
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
                                                'Recipient[account_name]',
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
      'template'=>'{update}{list}{delete}',
      'buttons'=>array
      (
          'list' => array
          (
          'options'=>array('title'=>'List messages'),
            'label'=>'<i class="icon-tasks icon-large"></i>',
            'url'=>'Yii::app()->createUrl("recipient/list", array("id"=>$data->id))',
          ),
      ),			
		),
	),
)); ?>
