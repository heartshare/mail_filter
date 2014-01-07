<?php

/**
 * This is the model class for table "{{alert_keyword}}".
 *
 * The followings are the available columns in table '{{alert_keyword}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $keyword
 * @property string $created_at
 * @property string $modified_at
 */
class AlertKeyword extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AlertKeyword the static model class
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
		return '{{alert_keyword}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('keyword, modified_at', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('keyword', 'length', 'max'=>255),
			array('created_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, keyword, created_at, modified_at', 'safe', 'on'=>'search'),
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
			'keyword' => 'Keyword',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('keyword',$this->keyword,true);
		$criteria->compare('created_at',$this->created_at,true);
		$criteria->compare('modified_at',$this->modified_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function scan($msg) {
	  // scans subject line and email address for each keyword
	  $kws = AlertKeyword::model()->findAll();
	  $email = $msg['personal'].' '.$msg['mailbox'].$msg['host'];
	  foreach ($kws as $k) {
	    if (stristr($msg['subject'],$k->keyword)!==false
	      or stristr($email,$k->keyword)!==false) {
	      return true;	      
	    }
	  }
	  return false;
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