<?php
$this->breadcrumbs=array(
	'Folders',
);

$this->menu=array(
	array('label'=>'Create Folder','url'=>array('create')),
);
?>

<h1>Folders</h1>

<?php

/*  $cntrlr = Yii::app()->controller->action->id; 
      $gridColumns = array(      
      	array('class'=>'CLinkColumn','labelExpression'=>'$data->name', 'header'=>'Name','urlExpression'=>'Yii::app()->baseUrl."/folder/update/$data->id"'),
      	array('class'=>'CLinkColumn','labelExpression'=>'$data->account->name', 'header'=>'Account','urlExpression'=>'Yii::app()->baseUrl."/folder/update/$data->id"'),
     array(            
            'name'=>'account_name',
            'header'=>'Account',
            'filter'=>CHtml::listData(Account::model()->findAll(),'name','name'),
            'value'=>'$data->account_name',
        ),
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
              'url'=>'Yii::app()->createUrl("folder/update", array("id"=>$data->id))',
            ),
                  'delete' => array
                  (
                  'options'=>array('title'=>'trash'),
                    'label'=>'<i class="icon-trash icon-large" style="margin:5px;"></i>',
                    'url'=>'Yii::app()->createUrl("folder/delete", array("id"=>$data->id))',
                  ),
              ),			
        ),

      );
    
 $this->widget('bootstrap.widgets.TbGridView', array(
	'type'=>'striped',
		'hideHeader'=>false,
	'dataProvider'=>$model,
	'filter'=>$model,	
	'template'=>"{items}\n{pager}",
	'columns'=>$gridColumns,
));

*/

 $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'folder-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(      
	  'name',
    array(            
        'name'=>'account_name',
        'header'=>'Account',
  'filter'=>CHtml::dropDownList(
                                  'Folder[account_name]',
                                  $model->account_name,
                                  CHtml::listData(
                                          Account::model()->findAll(),
                                          'name',
                                          'name'),array('empty' => 'All')),
        'value'=>'$data->account_name',
    ),
  	  array(
      	'class'=>'bootstrap.widgets.TbButtonColumn',
      'htmlOptions'=>array('width'=>'100px'),  		
    	'header'=>'Options',
      'template'=>'{update} {train} {delete}',
      'buttons'=>array
      (
        'train' => array
        (
          'options'=>array('title'=>'Train folder'),
          'label'=>'<i class="icon-filter icon-large" ></i>',
          'url'=>'Yii::app()->createUrl("folder/train", array("id"=>$data->id))',
        ),
      ),
    ),
  ),
)); ?>

