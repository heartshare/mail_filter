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
<?php 

if (Yii::app()->user->hasFlash('trained')) {
  $this->widget('bootstrap.widgets.TbAlert', array(
      'block'=>true, // display a larger alert block?
      'fade'=>true, // use transitions?
      'closeText'=>'×', // close link text - if set to false, no close link is displayed
      'alerts'=>array( // configurations per alert type
  	    'trained'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), // success, info, warning, error or danger
      ),
  ));
  
}

?>

<div>
  <div class="right">
    </div>
  <div class="left;">
  <?php
  $this->widget('bootstrap.widgets.TbButtonGroup', array(
    'type' => 'success',
        'toggle' => 'radio',
            'buttons'=>array(
                array('label'=>'Untrained', 'url'=>Yii::app()->getBaseUrl(true).'/sender/index'),
                array('label'=>'Trained', 'url'=>Yii::app()->getBaseUrl(true).'/sender/trained'),
                array('label'=>'Recent', 'url'=>Yii::app()->getBaseUrl(true).'/sender/recent','htmlOptions'=> array('class'=>'active')),
      ),
  ));
  ?>
</div>
</div>
<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'sender-form',
	'enableAjaxValidation'=>false,
)); ?>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'sender-grid',
	'dataProvider'=>$model->recent()->search(),
	'filter'=>$model,
	'columns'=>array(
    array('class'=>'CCheckBoxColumn','selectableRows'=>2),	  
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
<div class="form-actions">

<?php 
    echo CHtml::activeLabel($model,'folder_id',array('label'=>'Train to Folder:')); 
    echo CHtml::activeDropDownList($model,'folder_id',Sender::model()->getFolderOptions(),array('empty'=>'Select a Folder'));
    echo '<br />';
   $this->widget('bootstrap.widgets.TbButton', array(
	'buttonType'=>'submit',
	'type'=>'primary',
	'label'=>'Save',
)); ?>

</div>
<?php $this->endWidget(); ?>
