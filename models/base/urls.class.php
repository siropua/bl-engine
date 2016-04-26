<?php

use ble\baseTableModel;

class basemodel_urls extends baseTableModel
{
	static protected $pKey = 'id';
	static protected $tableName = 'urls';

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
  'url' => 
  array (
    'name' => 'url',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => '',
  ),
  'url_hash' => 
  array (
    'name' => 'url_hash',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => true,
    'is_unsigned' => false,
    'default' => '',
  ),
  'url_type' => 
  array (
    'name' => 'url_type',
    'type' => 'enum',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => 'none',
  ),
  'handler' => 
  array (
    'name' => 'handler',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => true,
    'is_unsigned' => false,
    'default' => '',
  ),
  'handled_id' => 
  array (
    'name' => 'handled_id',
    'type' => 'int',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'valid_from' => 
  array (
    'name' => 'valid_from',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'valid_to' => 
  array (
    'name' => 'valid_to',
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
);

}