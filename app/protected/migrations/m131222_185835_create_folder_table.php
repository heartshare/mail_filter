<?php

class m131222_185835_create_folder_table extends CDbMigration
{
     protected $MySqlOptions = 'ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci';
     public $tablePrefix;
     public $tableName;

     public function before() {
       $this->tablePrefix = Yii::app()->getDb()->tablePrefix;
       if ($this->tablePrefix <> '')
         $this->tableName = $this->tablePrefix.'folder';
     }

   	public function safeUp()
   	{
   	  $this->before();
    $this->createTable($this->tableName, array(
               'id' => 'pk',
               'account_id' => 'integer default 0',
               'user_id' => 'integer default 0',               
               'name' => 'string NOT NULL' ,              
               'train' => 'TINYINT default 0',
               'digest' => 'TINYINT default 0',
               'created_at' => 'DATETIME NOT NULL DEFAULT 0',
               'modified_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
               
                 ), $this->MySqlOptions);
                 $this->addForeignKey('fk_folder_account', $this->tableName, 'account_id', $this->tablePrefix.'account', 'id', 'CASCADE', 'CASCADE');
                 $this->addForeignKey('fk_folder_user', $this->tableName, 'user_id', $this->tablePrefix.'users', 'id', 'CASCADE', 'CASCADE');

   	}

   	public function safeDown()
   	{
   	  	$this->before();
   	  	$this->dropForeignKey('fk_folder_account', $this->tableName);
   	  	$this->dropForeignKey('fk_folder_user', $this->tableName);
   	    $this->dropTable($this->tableName);
   	}
}