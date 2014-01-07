<?php

class ActivationController extends Controller
{
	public $defaultAction = 'activation';

	
	/**
	 * Activation user account
	 */
	public function actionActivation () {
		$email = $_GET['email'];
		$activkey = $_GET['activkey'];
		
		if ($email&&$activkey) {
			
			$find = User::model()->notsafe()->findByAttributes(array('email'=>$email));
			if (isset($find)&&$find->status) {			    
			    $this->autoLogin($find->username);
			    // account already active
			    UserSetting::model()->initialize($find->id);    			
    			Yii::app()->user->setFlash('success','Congratulations! Your account is now active.');
    	    $this->redirect('/message/index');
			    //$this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Your account is active.")));
			} elseif(isset($find->activkey) && ($find->activkey==$activkey)) {
				$find->activkey = UserModule::encrypting(microtime());
				$find->status = 1;
				$find->save();
		    UserSetting::model()->initialize($find->id);
		    $this->autoLogin($find->username);
		    
  			Yii::app()->user->setFlash('success','Congratulations! Your account is now active.');
  	    $this->redirect('/message/index');
			//    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Your account is activated.")));    			
			} else {
			    $this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL. Please email support@".Yii::app()->params['mail_domain']." if you need assistance.")));
			}
		} else {
			$this->render('/user/message',array('title'=>UserModule::t("User activation"),'content'=>UserModule::t("Incorrect activation URL. Please email support@".Yii::app()->params['mail_domain']." if you need assistance.")));
		}
	}

  public function autoLogin($username) {
    $identity=new UserIdentity($username,null);
    $identity->skip_auth= true;
    $identity->authenticate();
    Yii::app()->user->login($identity);
		$identity->authenticate();
  }
}