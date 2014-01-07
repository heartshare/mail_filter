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
<div class="left">
<?php
$this->widget('bootstrap.widgets.TbButtonGroup', array(
  'type' => 'success',
      'toggle' => 'radio',
          'buttons'=>array(
            array('label'=>'Recent', 'url'=>Yii::app()->getBaseUrl(true).'/sender/recent'),            
              array('label'=>'Untrained', 'url'=>Yii::app()->getBaseUrl(true).'/sender/index', 'htmlOptions'=> array('class'=>'active')),
              array('label'=>'Trained', 'url'=>Yii::app()->getBaseUrl(true).'/sender/trained'),
    ),
));
?>
</div>
<br />
</div>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'sender-grid',
	'dataProvider'=>$model->untrained()->search('message_count desc'),
	'filter'=>$model,
	'columns'=>array(
		'personal',
		'email',
  	array('class'=>'CDataColumn','name'=> 'message_count', 'header'=>'# Msgs'),
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
<?php
/*  $cntrlr = Yii::app()->controller->action->id; 
      $gridColumns = array(   
array('class'=>'CLinkColumn','labelExpression'=>'getFriendlyEmail($data->personal,$data->email)', 'header'=>'Personal','urlExpression'=>'Yii::app()->baseUrl."/sender/update/$data->id"','htmlOptions'=>array('width'=>'400px')),
      	array('class'=>'CLinkColumn','labelExpression'=>'$data->account->name', 'header'=>'Account','urlExpression'=>'Yii::app()->baseUrl."/sender/update/$data->id"'),

      	        array(
          'htmlOptions'=>array('width'=>'100px'),  		
        	'class'=>'bootstrap.widgets.TbButtonColumn',
        	'header'=>'Options',
          'template'=>'{update}{delete}',
              'buttons'=>array
              (
                  'update' => array
                  (
                    'options'=>array('title'=>'Update properties'),
                    'label'=>'<i class="icon-pencil icon-large" style="margin:5px;"></i>',
                    'url'=>'Yii::app()->createUrl("sender/update", array("id"=>$data->id))',
                  ),
              
                  'delete' => array
                  (
                  'options'=>array('title'=>'trash'),
                    'label'=>'<i class="icon-trash icon-large" style="margin:5px;"></i>',
                    'url'=>'Yii::app()->createUrl("sender/delete", array("id"=>$data->id))',
                  ),
              ),			
        ),

      );
    
 $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped',
		'hideHeader'=>false,
	'dataProvider'=>$dataProvider,
	'template'=>"{items}\n{pager}",
	'columns'=>$gridColumns,
));
*/
?>