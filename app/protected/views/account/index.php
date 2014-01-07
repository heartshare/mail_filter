<?php
$this->breadcrumbs=array(
	'Accounts'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'Create Account','url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('account-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>E-mail Accounts</h1>

<?php $this->widget('bootstrap.widgets.TbGridView',array(
	'id'=>'account-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'name',
		'address',
  	array('class'=>'CDataColumn','value'=>'getProvider($data->provider)', 'header'=>'Provider',),
		
/*    array(
            'header' => 'custom',
            'type'=>'raw',
             'value' => '($data->id > 10) ? "<a><span class=\"icon-gift\"></span></a>" : "<a><span class=\"icon-camera\"></span></a>"',
        ),
        */
      	        array(
          'htmlOptions'=>array('width'=>'100px'),  		
        	'class'=>'bootstrap.widgets.TbButtonColumn',
        	'header'=>'Options',
          'template'=>'{update} {initialize} {list}  {clean} {delete}',
              'buttons'=>array
              (
                  'initialize' => array
                  (
                    'options'=>array('title'=>'Initialize folders'),
                    'label'=>'<i class="icon-folder-open icon-large" ></i>',
                    'url'=>'Yii::app()->createUrl("account/initialize", array("id"=>$data->id))',
                  ),
                  'list' => array
                  (
                    'options'=>array('title'=>'List remote folders'),
                    'label'=>'<i class="icon-tasks icon-large" ></i>',
                    'url'=>'Yii::app()->createUrl("account/list", array("id"=>$data->id))',
                  ),
                  'clean' => array
                  (
                    'options'=>array('title'=>'Process review folder'),
                    'label'=>'<i class="icon-download icon-large" ></i>',
                    'url'=>'Yii::app()->createUrl("account/clean", array("id"=>$data->id))',
                  ),
              
              ),			
        ),

	),
)); ?>

<?php 
  Yii::app()->user->setFlash('gmail', '<p><em><strong>Important:</strong> For security reasons, Google requires a few extra manual steps to enable you to access your gmail via IMAP from a remote server. <a href="http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/configuring-gmail/">Learn more about configuring your gmail account</a>.</em></p>');
  $this->widget('bootstrap.widgets.TbAlert', array(
      'alerts'=>array( // configurations per alert type
  	    'gmail'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
      ),
  ));
?>
