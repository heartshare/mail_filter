<?php

/**
 * This is the model class for table "{{recipient}}".
 *
 * The followings are the available columns in table '{{recipient}}':
 * @property integer $id
 * @property integer $account_id
 * @property integer $user_id
 * @property string $email
 * @property integer $folder_id
 * @property string $created_at
 * @property string $modified_at
 *
 * The followings are the available model relations:
 * @property Account $account
 * @property Users $user
 */
class Recipient extends CActiveRecord
{
  public $message_count;
  public $account_name;
  public $folder_name;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Recipient the static model class
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
		return '{{recipient}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, modified_at', 'required'),
			array('account_id, user_id, folder_id', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>255),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, account_id, user_id, email, folder_id, created_at, modified_at,message_count,account_name,folder_name', 'safe', 'on'=>'search'),
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
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
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
			'email' => 'Email',
			'folder_id' => 'Folder',
			'created_at' => 'Created At',
			'modified_at' => 'Modified At',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search($default_sort='message_count desc')
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;
		$account_table = Account::model()->tableName();
    $account_name_sql = "(select name from $account_table acct where acct.id = t.account_id)";
		$message_table = Message::model()->tableName();
    $message_count_sql = "(select count(*) from $message_table mt where mt.recipient_id = t.id)";
		$folder_table = Folder::model()->tableName();
    $folder_name_sql = "(select name from $folder_table ft where ft.id = t.folder_id)";
    $criteria->select = array(
            '*',
            $message_count_sql . " as message_count",
            $account_name_sql . " as account_name",
            $folder_name_sql . " as folder_name",
        );    

		$criteria->compare('id',$this->id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('folder_id',$this->folder_id);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);
    // where
    $criteria->compare($message_count_sql, $this->message_count);		
    $criteria->compare($account_name_sql, $this->account_name);		
    $criteria->compare($folder_name_sql, $this->folder_name);		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination' => array(
                  'pageSize' => Yii::app()->params['postsPerPage'],
             ),
      'sort' => array(
        'defaultOrder' => $default_sort,
                        'attributes' => array(
                            // order by
                            'message_count' => array(
                                'asc' => 'message_count ASC',
                                'desc' => 'message_count DESC',
                            ),
                            'account_name' => array(
                                'asc' => 'account_name ASC',
                                'desc' => 'account_name DESC',
                            ),
                            'folder_name' => array(
                                'asc' => 'folder_name ASC',
                                'desc' => 'folder_name DESC',
                            ),
                            '*',
                        ),
                    ),        
		));
	}
	
	public function add($user_id,$account_id,$email,$folder_id=0) {
	  if ($email=='') return false;
    $rx = Recipient::model()->findByAttributes(array('account_id'=>$account_id,'email'=>$email));
    if (empty($rx)) {
      $recipient = new Recipient;
      $recipient->user_id = $user_id;
      $recipient->account_id = $account_id;
      $recipient->email = $email;
      $recipient->folder_id = $folder_id;
      $recipient->created_at =new CDbExpression('NOW()'); 
      $recipient->modified_at =new CDbExpression('NOW()');          
      $recipient->save();
      $recipient_id = $recipient->id;
    } else {
      $recipient_id = $rx->id;
    }
    return $recipient_id;
  }
  
  public function markAsDeleted($id) {
    $mad = Yii::app()->db->createCommand()->update(Yii::app()->getDb()->tablePrefix.'recipient',array('is_deleted'=>1),'id=:id', array(':id'=>$id));
  }
  
	// scopes
  public function scopes()
      {
          return array(            
            'active'=>array(
                'condition'=>'is_deleted=0', 
            ),
            'deleted'=>array(
                'condition'=>'is_deleted=1', 
            ),
              'trained'=>array(
                  'condition'=>'folder_id>0', 
              ),
              'untrained'=>array(
                  'condition'=>'folder_id=0', 
              ),
          );
      }		  
 
       public function getFolderOptions($account_id = 0)
       {
         $foldersArray = CHtml::listData(Folder::model()->findAllByAttributes(array('account_id'=>$account_id)), 'id', 'name');
         return $foldersArray;
      }	
  
}