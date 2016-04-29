<?php

use ble\baseTableModel;

class basemodel_articlesSections extends baseTableModel
{
	static protected $pKey = 'id';
	static protected $tableName = 'articles_sections';

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
  'article_id' => 
  array (
    'name' => 'article_id',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => true,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'type' => 
  array (
    'name' => 'type',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => '',
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
  'text_data' => 
  array (
    'name' => 'text_data',
    'type' => 'longtext',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'text_data1' => 
  array (
    'name' => 'text_data1',
    'type' => 'text',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'text_data2' => 
  array (
    'name' => 'text_data2',
    'type' => 'text',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'string_data' => 
  array (
    'name' => 'string_data',
    'type' => 'varchar',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'string_data1' => 
  array (
    'name' => 'string_data1',
    'type' => 'varchar',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'string_data2' => 
  array (
    'name' => 'string_data2',
    'type' => 'varchar',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'int_data' => 
  array (
    'name' => 'int_data',
    'type' => 'int',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'int_data1' => 
  array (
    'name' => 'int_data1',
    'type' => 'int',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'int_data2' => 
  array (
    'name' => 'int_data2',
    'type' => 'int',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'float_data' => 
  array (
    'name' => 'float_data',
    'type' => 'double',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'float_data1' => 
  array (
    'name' => 'float_data1',
    'type' => 'double',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'float_data2' => 
  array (
    'name' => 'float_data2',
    'type' => 'double',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
);

}