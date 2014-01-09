<?php

/**
 * This is the model class for table "{{folder}}".
 *
 * The followings are the available columns in table '{{folder}}':
 * @property integer $id
 * @property integer $account_id
 * @property integer $user_id
 * @property string $name
 * @property integer $train
 * @property integer $digest
 * @property string $created_at
 * @property string $modified_at
 *
 * The followings are the available model relations:
 * @property Users $user
 * @property Account $account
 */
class Folder extends CActiveRecord
{
  public $account_name;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Folder the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{folder}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, modified_at', 'required'),
			array('account_id, user_id, train, digest', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>255),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_id, user_id, name, train, digest, created_at, modified_at, account_name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'account_id' => 'Account',
			'user_id' => 'User',
			'name' => 'Name',
			'train' => 'Train',
			'digest' => 'Digest',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$account_table = Account::model()->tableName();
    $account_name_sql = "(select name from $account_table acct where acct.id = t.account_id)";
    $criteria->select = array(
            '*',
            $account_name_sql . " as account_name",
        );    
		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('train',$this->train);
		$criteria->compare('digest',$this->digest);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);
    $criteria->compare($account_name_sql, $this->account_name);		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination' => array(
                  'pageSize' => Yii::app()->params['postsPerPage'],
             ),
       'sort' => array(
         'defaultOrder' => 'account_name asc, t.name asc',
                         'attributes' => array(
                             // order by
                             'account_name' => array(
                                 'asc' => 'account_name ASC',
                                 'desc' => 'account_name DESC',
                             ),
                             '*',
                         ),
                     ),        
		));
	}
		
	public function lookup($account_id,$name) {
	  // lookup folder by account and name
    $f = Folder::model()->findByAttributes(array('account_id'=>$account_id,'name'=>$name));
	  if (empty($f)) {
	    return 0;
	  }	else {
	    return $f['id'];
	  }	  
	}
	
  public function add($user_id,$account_id,$name,$train) {
    $f = Folder::model()->findByAttributes(array('user_id'=>$user_id,'account_id'=>$account_id,'name'=>$name));
	  if (empty($f)) {
	    $folder = new Folder;
	    $folder->user_id = $user_id;
	    $folder->account_id = $account_id;
	    $folder->name = $name;
	    $folder->train = $train;
	    $folder->digest = 1;
      $folder->created_at =new CDbExpression('NOW()'); 
      $folder->modified_at =new CDbExpression('NOW()');          
      $folder->save();
	    $folder_id = $folder->id;
	  } else {
	    $folder_id = $f['id'];
	  }
	  return $folder_id;
  }
	
  public function initialize($account_id) {
    // create the default filtering mailboxes in remote account
    $r = new Remote();
    $account = Account::model()->findByPk($account_id);
    $user_id = $account->user_id;
    $provider = $account->provider;
    $this->add($user_id,$account_id,'Inbox',1);
    if ($provider == Account::PROVIDER_FASTMAIL) {
      // These are the folders we train by default
      $this->add($user_id,$account_id,'Inbox.+Filtering.Bulk',1);
      $this->add($user_id,$account_id,'Inbox.+Filtering.Zap',1);    
      $folders = array('Inbox.+Filtering','Inbox.+Filtering.Bulk','Inbox.+Filtering.Review','Inbox.+Filtering.Zap');      
    } else if ($provider == Account::PROVIDER_GMAIL) {
      $this->add($user_id,$account_id,'+Filtering/Bulk',1);
      $this->add($user_id,$account_id,'+Filtering/Zap',1);    
      $folders = array('+Filtering','+Filtering/Bulk','+Filtering/Review','+Filtering/Zap');
    }
    // create the folders remotely in the mailbox    
    foreach ($folders as $f) {
      // create remote imap folder
      $r->createMailbox($account_id,$f);
    }
  }

  public function initialize_private($user_id,$account_id) {
    // initialize private folder
    $r = new Remote();
    $r->setDefaultPaths($account_id);
    $this->add($user_id,$account_id,$r->path_private,1);
    $r->createMailbox($account_id,$r->path_private);
  }
  
  public function train($id) {
    // train folder based on existing contents
    $r=new Remote();
    $results = $r->trainFolder($id);
    return $results;
  }
 
  public function createRemote($account_id,$name) {
    // create the default filtering mailboxes in remote account
    $r = new Remote();
    $account = Account::model()->findByPk($account_id);
    $user_id = $account->user_id;
    $provider = $account->provider;
    if ($provider == Account::PROVIDER_FASTMAIL) {
      if (strtolower(substr($name,0,6))<>'inbox.')
        $name = 'Inbox.'.$name;
    } else if ($provider == Account::PROVIDER_GMAIL) {
      // no changes
    }
    $result = $r->createMailbox($account_id,$name);
    return $result;
  }
  
  public function renameRemote($account_id,$current_name,$new_name) {
    $r = new Remote();
    $result = $r->renameMailbox($account_id,$current_name,$new_name);
    return $result;
  }
  // scoping functions
  public function owned_by($user_id = 0)
  {
    $this->getDbCriteria()->mergeWith( array(
      'condition'=>'user_id='.$user_id,
    ));
      return $this;
  }
  
	
}