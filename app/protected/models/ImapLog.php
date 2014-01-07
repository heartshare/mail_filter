<?php

/**
 * This is the model class for table "{{imap_log}}".
 *
 * The followings are the available columns in table '{{imap_log}}':
 * @property integer $id
 * @property string $process
 * @property string $notes
 * @property string $xtime
 * @property string $modified_at
 */
class ImapLog extends CActiveRecord
{
   
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ImapLog the static model class
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
		return '{{imap_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('process,  modified_at', 'required'),
			array('process, notes', 'length', 'max'=>255),
			array('xtime', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, process, notes, xtime, modified_at', 'safe', 'on'=>'search'),
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
			'process' => 'Process',
			'notes' => 'Notes',
			'xtime' => 'Xtime',
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
		$criteria->compare('process',$this->process,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('xtime',$this->xtime,true);
		$criteria->compare('modified_at',$this->modified_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function add($process='',$notes='',$xtime = 0) {
    $il = new ImapLog();
    $il->process=$process;
    $il->notes=$notes;
    $il->xtime=intval($xtime);
    $il->modified_at =new CDbExpression('NOW()');          
    $il->save();
  }	
}