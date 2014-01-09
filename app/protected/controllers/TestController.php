<?php

class TestController extends Controller
{
  public $tablePrefix;
  public $tableName;
  
	public $layout='//layouts/main';

  /**
   * @return array action filters
   */
  public function filters()
  {
  	return array(
  		'accessControl', // perform access control for CRUD operations
  		'setup + populate + setEid'
  	);
  }

   public function filterSetup($filterChain)
  {
    $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
         $filterChain->run();
  }

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform actions
				'actions'=>array('parse','test','review','folder'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 
				'actions'=>array(''),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 
				'actions'=>array('populate,setEid,thumb'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	public function actionFolder() {
	}
	
	public function actionReview() {
	}
	
	public function actionTest() {
	}
	
	public static function actionParse() {
	}
	
	public function actionThumb() {
	}
	
	public function actionPopulate() {
	}

  private function addPlace($place) {
  }
  
  private function addUser($user) {
    
    }

    private function addMember($email,$slug) {
   }


}