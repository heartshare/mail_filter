<?php

/**
 * This is the model class for table "{{private_message}}".
 *
 * The followings are the available columns in table '{{private_message}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $account_id
 * @property integer $folder_id
 * @property integer $sender_id
 * @property string $message_id
 * @property string $subject
 * @property string $body_text
 * @property string $body_html
 * @property integer $udate
 * @property integer $status
 * @property string $created_at
 * @property string $modified_at
 *
 * The followings are the available model relations:
 * @property Folder $folder
 * @property Account $account
 * @property Sender $sender
 * @property Users $user
 */
class PrivateMessage extends CActiveRecord
{
  const STATUS_CREATED=0;
  public $email;
  
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PrivateMessage the static model class
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
		return '{{private_message}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message_id, modified_at', 'required'),
			array('user_id, account_id, folder_id, sender_id, udate, status', 'numerical', 'integerOnly'=>true),
			array('message_id', 'length', 'max'=>255),
			array('subject, body_text, body_html, created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, account_id, folder_id, sender_id, message_id, subject, body_text, body_html, udate, status, created_at, modified_at,email', 'safe', 'on'=>'search'),
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
			'folder' => array(self::BELONGS_TO, 'Folder', 'folder_id'),
			'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
			'sender' => array(self::BELONGS_TO, 'Sender', 'sender_id'),
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
			'user_id' => 'User',
			'account_id' => 'Account',
			'folder_id' => 'Folder',
			'sender_id' => 'Sender',
			'message_id' => 'Message',
			'subject' => 'Subject',
			'body_text' => 'Body Text',
			'body_html' => 'Body Html',
			'udate' => 'Udate',
			'status' => 'Status',
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
		$sender_table = Sender::model()->tableName();
    $email_sql = "(select CONCAT(personal,' <',email,'>') from $sender_table st where st.id = t.sender_id)";
    $criteria->select = array(
            '*',
            $email_sql . " as email",
        );
		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('account_id',$this->account_id);
		$criteria->compare('folder_id',$this->folder_id);
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('message_id',$this->message_id,true);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('body_text',$this->body_text,true);
		$criteria->compare('body_html',$this->body_html,true);
		$criteria->compare('udate',$this->udate);
		$criteria->compare('status',$this->status);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);
    $criteria->compare($email_sql, $this->email,true);		    

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
      'pagination' => array(
                  'pageSize' => Yii::app()->params['postsPerPage'],
             ),
      'sort' => array(
        'defaultOrder' => 'udate desc',
          'attributes' => array(
              // order by
              'udate' => array(
                  'asc' => 'udate ASC',
                  'desc' => 'udate DESC',
              ),
              // order by
              'email' => array(
                  'asc' => 'email ASC',
                  'desc' => 'email DESC',
              ),
              '*',
          ),        
      ),
		));
	}

  public function owned_by($user_id = 0)
  {
    $this->getDbCriteria()->mergeWith( array(
      'condition'=>'user_id='.$user_id,
    ));
      return $this;
  }
	
	public function initialize() {
	  $f = new Folder();
    $users = User::model()->findAll();
    foreach ($users as $user) {
      $user_id = $user['id'];
      $accounts = Account::model()->findAllByAttributes(array('user_id'=>$user_id));
      foreach ($accounts as $account) {
        $account_id = $account['id'];  
    	  $f->initialize_private($user_id,$account_id);
      } // end accounts
    } // end users
	}
	
  public function add($user_id,$account_id,$folder_id,$sender_id,$message_id,$subject,$body_text,$body_html,$udate) {
    $pm = PrivateMessage::model()->findByAttributes(array('account_id'=>$account_id,'message_id'=>$message_id));
    if (empty($pm)) {
      $pm = new PrivateMessage;
      $pm->user_id = $user_id;
      $pm->account_id = $account_id;
      $pm->folder_id = $folder_id;
      $pm->sender_id = $sender_id;
      $pm->message_id = $message_id;
      $pm->subject = $subject;
      $pm->body_text = $body_text;
      $pm->body_html = $body_html;
      $pm->udate = $udate;
      $pm->status = self::STATUS_CREATED;
      $pm->created_at =new CDbExpression('NOW()'); 
      $pm->modified_at =new CDbExpression('NOW()');          
      $pm->save();
      $message_id = $pm->id;
    } else {
      $message_id = $pm->id;
    }
    return $message_id;
  }	
}