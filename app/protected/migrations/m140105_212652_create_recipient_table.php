<?php

class m140105_212652_create_recipient_table extends CDbMigration
{
   protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
   public $tablePrefix;
   public $tableName;

   public function before() {
     $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
     if ($this->tablePrefix <> '')
       $this->tableName = $this->tablePrefix.'recipient';
   }

 	public function safeUp()
 	{
 	  $this->before();
  $this->createTable($this->tableName, array(
             'id' => 'pk',
             'account_id' => 'integer default 0',
             'user_id' => 'integer default 0',
             'email' => 'string NOT NULL',
             'folder_id'=> 'INTEGER DEFAULT 0',
             'is_deleted' => 'TINYINT DEFAULT 0',
             'created_at' => 'DATETIME NOT NULL DEFAULT 0',
             'modified_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',

               ), $this->MySqlOptions);
               $this->createIndex('recipient_email', $this->tableName , 'account_id,email', true);
               $this->addForeignKey('fk_recipient_user', $this->tableName, 'user_id', $this->tablePrefix.'users', 'id', 'CASCADE', 'CASCADE');
               $this->addForeignKey('fk_recipient_account', $this->tableName, 'account_id', $this->tablePrefix.'account', 'id', 'CASCADE', 'CASCADE');
 	}

 	public function safeDown()
 	{
 	  	$this->before();
 	  	$this->dropForeignKey('fk_recipient_user', $this->tableName);
 	  	$this->dropForeignKey('fk_recipient_account', $this->tableName);
      $this->dropIndex('recipient_email', $this->tableName);
 	    $this->dropTable($this->tableName);
 	}
}