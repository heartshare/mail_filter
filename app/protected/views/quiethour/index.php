<?php
$this->breadcrumbs=array(
	'Quiet Hours',
);

$this->menu=array(
	array('label'=>'Add an interval','url'=>array('create')),
);
?>

<h1>Quiet Hours</h1>

<?php
  if (Yii::app()->params['version']=='basic') {
      Yii::app()->user->setFlash('upgrade', '<p><em>The Quiet Hours feature requires the advanced module to operate. <a href="/site/page?view=upgrade">Learn more</a>.</em></p>');
      $this->widget('bootstrap.widgets.TbAlert', array(
          'alerts'=>array( // configurations per alert type
      	    'upgrade'=>array('block'=>true, 'fade'=>true, 'closeText'=>'×'), 
          ),
      ));
    }
?>

<?php
  $cntrlr = Yii::app()->controller->action->id; 
      $gridColumns = array(      
      	  array('class'=>'CDataColumn','value'=> 'getQuietHourDay($data->days)', 'header'=>'Days'),
          'start_interval',
          'end_interval',
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
              'url'=>'Yii::app()->createUrl("quiethour/update", array("id"=>$data->id))',
            ),
                  'delete' => array
                  (
                  'options'=>array('title'=>'trash'),
                    'label'=>'<i class="icon-trash icon-large" style="margin:5px;"></i>',
                    'url'=>'Yii::app()->createUrl("quiethour/delete", array("id"=>$data->id))',
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

?>
