<?php

use ble\baseTableModel;

class basemodel_articles extends baseTableModel
{
	static protected $pKey = 'id';
	static protected $tableName = 'articles';

	static protected $fields = array (
  'id' => 
  array (
    'name' => 'id',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => true,
    'is_index' => true,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'url_id' => 
  array (
    'name' => 'url_id',
    'type' => 'int',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => true,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'catalog_id' => 
  array (
    'name' => 'catalog_id',
    'type' => 'int',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => true,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'owner_id' => 
  array (
    'name' => 'owner_id',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'date_add' => 
  array (
    'name' => 'date_add',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'last_update' => 
  array (
    'name' => 'last_update',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'edits_count' => 
  array (
    'name' => 'edits_count',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'views' => 
  array (
    'name' => 'views',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'comments' => 
  array (
    'name' => 'comments',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => '0',
  ),
  'last_comment' => 
  array (
    'name' => 'last_comment',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'is_visible' => 
  array (
    'name' => 'is_visible',
    'type' => 'tinyint',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => '1',
  ),
  'is_deleted' => 
  array (
    'name' => 'is_deleted',
    'type' => 'tinyint',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => '0',
  ),
  'is_allow_comments' => 
  array (
    'name' => 'is_allow_comments',
    'type' => 'tinyint',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => '0',
  ),
  'show_till' => 
  array (
    'name' => 'show_till',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'comments_till' => 
  array (
    'name' => 'comments_till',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'public_time' => 
  array (
    'name' => 'public_time',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'status' => 
  array (
    'name' => 'status',
    'type' => 'enum',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => 'draft',
  ),
  'title' => 
  array (
    'name' => 'title',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => '',
  ),
  'kws' => 
  array (
    'name' => 'kws',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => '',
  ),
  'description' => 
  array (
    'name' => 'description',
    'type' => 'text',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'text' => 
  array (
    'name' => 'text',
    'type' => 'longtext',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'about_text' => 
  array (
    'name' => 'about_text',
    'type' => 'text',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'mainpic' => 
  array (
    'name' => 'mainpic',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => '',
  ),
  'original_link' => 
  array (
    'name' => 'original_link',
    'type' => 'text',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
);

}