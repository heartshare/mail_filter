<?php

class SenderController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('verify','program'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('recent','index','trained','view','create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Sender;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Sender']))
		{
			$model->attributes=$_POST['Sender'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
    $last_folder_id = $model->folder_id;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Sender']))
		{
			$model->attributes=$_POST['Sender'];
			$model->last_folder_id = $last_folder_id;
			$model->last_trained = time();
			if($model->save()) {
			  $r = new Remote;
			  $r->scanForSender($model,false);
		    Yii::app()->user->setFlash('updated','Your settings have been saved.');
				$this->redirect(array('update','id'=>$model->id));
			}
		}

		$this->render('update',array(
			'model'=>$model,'message_count'=>count($model->messages)
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
	  $this->layout = '//layouts/column1';
	  $model=new Sender('search');  		  	
	  if(isset($_POST['sender-grid_c0']) and !empty($_POST['Sender']['folder_id']))
	  {
	    $dest_folder_id = $_POST['Sender']['folder_id'];
	    $sender_list = $_POST['sender-grid_c0'];
	    foreach ($sender_list as $sender_id) {
	      // train sender to folder_id
	      $model->setFolderCheckAccount($sender_id,$dest_folder_id);
	    }
	    Yii::app()->user->setFlash('trained','Your selections have been trained. <em><strong>Note:</strong> Trainings set to folders in the wrong account will be ignored.</em>');
	    unset($_POST['Sender']['folder_id']);
		} 
  		$model->unsetAttributes();  // clear any default values
  		$model->user_id = Yii::app()->user->id;      		
  		$model->folder_id = 0;
  		if(isset($_GET['Sender']))
  			$model->attributes=$_GET['Sender'];
  		else if(isset($_POST['Sender']))
  			$model->attributes=$_POST['Sender'];
  		$this->render('index',array(
  			'model'=>$model->owned_by(Yii::app()->user->id),
  		));
	}
	
	public function actionRecent()
	{
	  $this->layout = '//layouts/column1';
  		$model=new Sender('search');  		
	  
	  if(isset($_POST['sender-grid_c0']) and !empty($_POST['Sender']['folder_id']))
	  {
	    $dest_folder_id = $_POST['Sender']['folder_id'];
	    $sender_list = $_POST['sender-grid_c0'];
	    foreach ($sender_list as $sender_id) {
	      // train sender to folder_id
	      $model->setFolderCheckAccount($sender_id,$dest_folder_id);
	    }
	    Yii::app()->user->setFlash('trained','Your selections have been trained. <em><strong>Note:</strong> Trainings set to folders in the wrong account will be ignored.</em>');		   
	    unset($_POST['Sender']['folder_id']);	     	    
		}  		
  		$model->unsetAttributes();  // clear any default values
  		$model->user_id = Yii::app()->user->id;      		
  		if(isset($_GET['Sender']))
  			$model->attributes=$_GET['Sender'];
  		else if(isset($_POST['Sender']))
  			$model->attributes=$_POST['Sender'];
  		$this->render('recent',array(
  			'model'=>$model,
  		));
	}

	public function actionTrained()
	{
	  $this->layout = '//layouts/column1';
	  	$model=new Sender('search');  		
  	
	  if(isset($_POST['sender-grid_c0']) and !empty($_POST['Sender']['folder_id']))
	  {
	    $dest_folder_id = $_POST['Sender']['folder_id'];
	    $sender_list = $_POST['sender-grid_c0'];
	    foreach ($sender_list as $sender_id) {
	      // train sender to folder_id
	      $model->setFolderCheckAccount($sender_id,$dest_folder_id);
	    }
	    Yii::app()->user->setFlash('trained','Your selections have been trained. <em><strong>Note:</strong> Trainings set to folders in the wrong account will be ignored.</em>');		 
	    unset($_POST['Sender']['folder_id']);
	    
		}  		
  		$model->unsetAttributes();  // clear any default values
  		$model->user_id = Yii::app()->user->id;      		
  		if(isset($_GET['Sender']))
  			$model->attributes=$_GET['Sender'];
  		else if(isset($_POST['Sender']))
  			$model->attributes=$_POST['Sender'];
  		$this->render('trained',array(
  			'model'=>$model,
  		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Sender('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Sender']))
			$model->attributes=$_GET['Sender'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

  public function actionVerify($s = 0, $m=0,$u=0) {
    // verify that secure msg url from digest is valid, log in user, show msg
    $sender_id = $s;
    $message_id = $m;
    $udate = $u;
    $msg = Message::model()->findByPk($message_id);
    if (!empty($msg) && $msg->sender_id == $sender_id && $msg->udate == $udate) {
      $result = 'Thank you for your assistance. I\'ll respond to your email as soon as possible.';
      $a = new Advanced();
      $a->verifySender($msg->account_id,$sender_id);
    } else {
      $result = 'Sorry, we could not verify your email address.';
    }
		$this->render('verify',array(
			'result'=>$result,
		));
  }

  public function actionProgram($f ='', $s = 0, $m=0,$u=0) {
    // program sender to folder_id if valid message link
    $folder_name = $f;
    $sender_id = $s;
    $message_id = $m;
    $udate = $u;
    // verify link properties from digest
    $msg = Message::model()->findByPk($message_id);
    if (!empty($msg) && $msg->sender_id == $sender_id && $msg->udate == $udate) {
      $r = new Remote();
      $r->setDefaultPaths($msg['account_id']);
      $fld = new Folder();
      if ($folder_name =='inbox') {
        $folder_path = $r->path_inbox;
      } else if ($folder_name =='bulk') {
        $folder_path = $r->path_bulk;
      } else if ($folder_name =='block') {
        $folder_path = $r->path_block;        
      }
      $folder_id = $fld->lookup($msg['account_id'],$folder_path);
      if ($folder_id>0) {
        $sm = new Sender();
        $sm->setFolder($sender_id,$folder_id);        
        $result = 'Sender updated. Thank you.';
      } else {
        $result = 'Sorry, invalid destination folder.';
      }
    } else {
      $result = 'Sorry, we could not verify your credentials.';
    }
		$this->render('program',array(
			'result'=>$result,
		));
  }


	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Sender::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='sender-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
