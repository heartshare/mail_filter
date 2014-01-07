<?php
 echo CHtml::beginForm(array('/request/create'));     
?>

        <?php echo CHtml::errorSummary(Request::model()); ?>
        <div id="newRequestHeader"><strong>New user?</strong> Request an invite</div>        
                <?php echo CHtml::activeTextField(Request::model(),'email', array('placeholder'=>'email','size' => 30)); ?>                
                <span id="home-request" >
                <?php
                $this->widget('bootstrap.widgets.TbButton',array(
                	'label' => 'Save',
                	'buttonType'=>'submit',
                	'type' => 'success',
                	'size' => 'small',
                )); 
                ?>
        </span>
<?php echo CHtml::endForm(); ?>

