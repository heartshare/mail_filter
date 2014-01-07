<div class="row">
  <div class="span1">
    </div>
    <div class="span10">

<div id="tutorial">
  <?php
  $this->pageTitle=Yii::app()->name . ' - How It Works';
  $this->beginWidget('bootstrap.widgets.TbHeroUnit', array(
  	'heading'=>'How It Works',
  ));  
$i=1;
?>
<p>Filtered helps reduce the clutter in your email and give you complete control: anonymized email addresses for privacy and one-click unsubscribe.</p>   

<?php

// if they just registered, ask them to verify their email address 
if(Yii::app()->user->hasFlash('pre_activation')) { ?>
  <p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Verify your email address. Check your inbox and click the activation link. <em>If you didn't receive an activation email when you signed up, check your junk mail folder or email support@filtered.io.</em></p>
<?php }  else if(Yii::app()->user->isGuest) { ?>
  <p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Start by <a href="<?php echo Yii::app()->baseUrl; ?>/user/registration">signing up</a>.</p>
  <p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Verify your email address. Check your inbox and click the activation link. <em>If you didn't receive an activation email when you signed up, check your junk mail folder or email support@filtered.io.</em></p>
  <?php } ?>

<p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Begin forwarding email to your filtered.io mail addresses.</p>
<p><span class="badge badge-success"><?php echo $i; $i++; ?></span> Watch as your inbox unclutters itself.</p>
<div class="center">
<?php
  if(!Yii::app()->user->hasFlash('pre_activation')) {
    if(Yii::app()->user->isGuest) {
      $this->widget('bootstrap.widgets.TbButton', array(
      	'type'=>'primary',
        'size'=>'large',  			
      	'label'=>'Sign Up Now',
      	'url' => Yii::app()->baseUrl.'/',
      ));
    } else {
      $this->widget('bootstrap.widgets.TbButton', array(
      	'type'=>'primary',
        'size'=>'large',  			
      	'label'=>'Visit Your Filtered Inbox',
      	'url' => Yii::app()->baseUrl.'/message/index',
      ));
    }
  }
?>
</div>
  </div>
    
  </p>
  <?php $this->endWidget(); ?> 
</div>
  <div class="span1">
  </div>
</div>

</div> <!-- end row -->