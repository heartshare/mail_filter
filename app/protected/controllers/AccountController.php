<?php

class AccountController extends Controller
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

  protected function beforeAction($action) {
    if (Yii::app()->user->isGuest)
      $this->redirect(Yii::app()->getBaseUrl(true));
    else
      return true;
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
				'actions'=>array(''),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'index','initialize','delete','list','clean'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
			'actions'=>array('view'),
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
		$model=new Account;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Account']))
		{
			$model->attributes=$_POST['Account'];
			$salt_cred = $model->createCredentials($_POST['Account']['username'],$_POST['Account']['password']);
			$model->salt=$salt_cred[0];
			$model->cred=base64_encode($salt_cred[1]);
			$model->user_id = Yii::app()->user->id;
      $model->created_at =new CDbExpression('NOW()'); 
      $model->modified_at =new CDbExpression('NOW()');
			if($model->save())
			  // initialize new folders - commented out due to gmail security issue
			  // best to do manually
			  //$f= new Folder();
        //$f->initialize($model->id);      
				$this->redirect(array('index'));
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

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Account']))
		{
			$model->attributes=$_POST['Account'];
			$salt_cred = $model->createCredentials($_POST['Account']['username'],$_POST['Account']['password']);
			$model->salt=$salt_cred[0];
			$model->cred=base64_encode($salt_cred[1]);
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
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
			$account = Account::model()->purge($id);
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
      		$model=new Account('search');
      		$model->unsetAttributes();  // clear any default values
      		$model->user_id = Yii::app()->user->id;      		
      		if(isset($_GET['Account']))
      			$model->attributes=$_GET['Account'];
      		$this->render('index',array(
      			'model'=>$model,
      		));
  }

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
	}

  public function actionInitialize($id) {
    $f= new Folder();
    $f->initialize($id);
    $this->redirect(array('index'));
  }
  
	public function actionClean($id)
	{
	    $r = new Remote();
	    $results = $r->processReviewFolders($id);
  		$this->render('clean',array(
  			'results'=>$results,
  		));
	}
  
  public function actionList($id) {
    $a = new Account();
    $remote_list = $a->listRemoteFolders($id);
    $this->render('list',array(
			'remote_list'=>$remote_list,
		));
  }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Account::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='account-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
