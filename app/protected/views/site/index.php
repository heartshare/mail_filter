<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
Yii::import('application.modules.user.UserModule');

?>
<div class="row span12">
  <div class="span12" style="min-height:150px;">
  </div>
</div> <!-- end row -->
<div class="row span12">
  <div class ="span2" >
    
    </div>
  <div class="span5">
    <div class="home-header">
    	  <h1>Welcome to Filtered</h1>
<p>Filtered is a free, open source IMAP-based mail filtering solution; a toybox for programmers to manage their email. <br /><a href="http://jeffreifman.com/filtered-open-source-imap-mail-filtering-software-for-php/">Learn more</a> </p>
    	</div>
    </div>  
    <div class="home-content span3">
    <?php       
  $this->widget('application.modules.user.components.LoginWidget'); 
/*
  if (Yii::app()->request->getQuery('fr', false) !==false) {
    $this->widget('application.modules.user.components.RegistrationWidget');  
  } else {
    ?>
    <div class="portlet" id="yw3">
      <div class="portlet-content">
    <?php
     echo CHtml::beginForm(array('/request/create'));     
    ?>
            <div id="newRequestHeader"><strong>New user?</strong> Request an invite</div>        
                    <?php echo CHtml::activeTextField(Request::model(),'email', array('placeholder'=>'email','size' => 15, 'width'=>15, 'htmlOptions'=>'width:25px;')); ?>                
                    <span id="home-request" >
                    <?php
                    $this->widget('bootstrap.widgets.TbButton',array(
                    	'label' => 'Submit',
                    	'buttonType'=>'submit',
                    	'type' => 'success',
                    	'size' => 'small',
                    )); 
                    ?>
            </span>
    <?php echo CHtml::endForm();
      }
      */
      
  ?>
    </div> <!-- end portlet-content -->
  </div> <!--end portlet for request -->
  </div>
  <div class="span2">
  </div>
  </div> <!-- end row -->
  <div class="row">
    <div class="span12" style="min-height:150px;">
    </div>
  </div>
  
</div>