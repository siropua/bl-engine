<?php

use ble\baseTableModel;

class basemodel_articlesCatalog extends baseTableModel
{
	static protected $pKey = 'id';
	static protected $tableName = 'articles_catalog';

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
  'parent_id' => 
  array (
    'name' => 'parent_id',
    'type' => 'int',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'left_key' => 
  array (
    'name' => 'left_key',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'right_key' => 
  array (
    'name' => 'right_key',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'level' => 
  array (
    'name' => 'level',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'order_n' => 
  array (
    'name' => 'order_n',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'url_id' => 
  array (
    'name' => 'url_id',
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
  'last_modified' => 
  array (
    'name' => 'last_modified',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
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
  'description' => 
  array (
    'name' => 'description',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => '',
  ),
  'keywords' => 
  array (
    'name' => 'keywords',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => '',
  ),
  'articles_count' => 
  array (
    'name' => 'articles_count',
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
  'last_article' => 
  array (
    'name' => 'last_article',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'last_article_id' => 
  array (
    'name' => 'last_article_id',
    'type' => 'int',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'last_content_updated' => 
  array (
    'name' => 'last_content_updated',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
);

}