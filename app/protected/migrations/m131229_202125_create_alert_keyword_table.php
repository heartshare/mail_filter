<?php

class m131229_202125_create_alert_keyword_table extends CDbMigration
{
	protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
   public $tablePrefix;
   public $tableName;

   public function before() {
     $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
     if ($this->tablePrefix <> '')
       $this->tableName = $this->tablePrefix.'alert_keyword';
   }

 	public function safeUp()
 	{
 	  $this->before();
  $this->createTable($this->tableName, array(
             'id' => 'pk',
             'user_id'=> 'INTEGER DEFAULT 0',
             'keyword'=>'string not null',
             'created_at' => 'DATETIME NOT NULL DEFAULT 0',
             'modified_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
               ), $this->MySqlOptions);
 	}

 	public function safeDown()
 	{
 	  	$this->before();
 	    $this->dropTable($this->tableName);
 	}
}