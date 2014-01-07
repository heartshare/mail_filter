<?php

class m131222_185831_create_account_table extends CDbMigration
{
   protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
   public $tablePrefix;
   public $tableName;

   public function before() {
     $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
     if ($this->tablePrefix <> '')
       $this->tableName = $this->tablePrefix.'account';
   }

 	public function safeUp()
 	{
 	  $this->before();
  $this->createTable($this->tableName, array(
             'id' => 'pk',
             'user_id' => 'integer default 0',
             'name' => 'string NOT NULL',
             'address' => 'string NOT NULL',
             'cred' => 'string NOT NULL',
             'salt' => 'string NOT NULL',
             'provider' => 'TINYINT DEFAULT 0',
             'last_checked' => 'DATETIME NOT NULL DEFAULT 0',
             'created_at' => 'DATETIME NOT NULL DEFAULT 0',
             'modified_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
               ), $this->MySqlOptions);
               $this->addForeignKey('fk_account', $this->tableName, 'user_id', $this->tablePrefix.'users', 'id', 'CASCADE', 'CASCADE');
 	}

 	public function safeDown()
 	{
 	  	$this->before();
 	  	$this->dropForeignKey('fk_account', $this->tableName);
 	    $this->dropTable($this->tableName);
 	}
 }