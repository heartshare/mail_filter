<?php

class m140114_194303_add_last_emailed_to_sender_table extends CDbMigration
{
	protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
    public $tablePrefix;
    public $tableName;

    public function before() {
      $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
      if ($this->tablePrefix <> '')
        $this->tableName = $this->tablePrefix.'sender';
    }

  	public function safeUp()
  	{
  	  $this->before();   	  
     $this->addColumn($this->tableName,'last_emailed','INTEGER DEFAULT 0');
  	}

  	public function safeDown()
  	{
  	  	$this->before();
       $this->dropColumn($this->tableName,'last_emailed');        
  	}
}