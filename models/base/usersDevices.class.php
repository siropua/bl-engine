<?php

use ble\baseTableModel;

class basemodel_usersDevices extends baseTableModel
{
	static protected $pKey = 'id';
	static protected $tableName = 'users_devices';

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
  'user_id' => 
  array (
    'name' => 'user_id',
    'type' => 'int',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => true,
    'is_unsigned' => true,
    'default' => NULL,
  ),
  'access_token' => 
  array (
    'name' => 'access_token',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => true,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'device_id' => 
  array (
    'name' => 'device_id',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => '',
  ),
  'app_id' => 
  array (
    'name' => 'app_id',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => '',
  ),
  'device_token' => 
  array (
    'name' => 'device_token',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'os_ver' => 
  array (
    'name' => 'os_ver',
    'type' => 'varchar',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'is_device_token_valid' => 
  array (
    'name' => 'is_device_token_valid',
    'type' => 'tinyint',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => true,
    'is_unsigned' => true,
    'default' => '1',
  ),
  'timezone' => 
  array (
    'name' => 'timezone',
    'type' => 'int',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'device_name' => 
  array (
    'name' => 'device_name',
    'type' => 'varchar',
    'is_null' => true,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => NULL,
  ),
  'class_name' => 
  array (
    'name' => 'class_name',
    'type' => 'enum',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => 'other',
  ),
  'locale' => 
  array (
    'name' => 'locale',
    'type' => 'varchar',
    'is_null' => false,
    'is_primary' => false,
    'is_index' => false,
    'is_unsigned' => false,
    'default' => '',
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
);

}