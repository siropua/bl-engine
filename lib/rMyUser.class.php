<?php

/**
 *
 *
 * @version $Id: myUser.class.php,v 1.2 2007/11/18 12:34:47 steel Exp $
 * @copyright 2007
 */


require_once('classes/rUser.class.php');

/**
 *
 *
 **/
class rMyUser extends rUser
{
 
 function __construct($db, $autoAuth = true)
 {
  parent::__construct($db, COOKIE_PREFIX, $autoAuth);
 }
 
 function doHit()
 {
  parent::doHit();
  $this->_db->query('UPDATE ?# SET last_online = ?d WHERE id = ?d',
			USERS_TABLE, time(), $this->_ID);
 }
 
function getInfo(){
  if(!$this->_ID) return array();
  $info = $this->_db->selectRow('SELECT * FROM users_info WHERE id = ?d', $this->_ID);
  if(!$info){
   $this->_db->query('INSERT INTO users_info SET id = ?d', $this->_ID);
   $info = $this->_db->selectRow('SELECT * FROM users_info WHERE id = ?d', $this->_ID);
  }
  
   list($info['byear'], $info['bmonth'], $info['bday']) = explode('-', @$info['birthday']);
  
  
  return $info;
  
 }
 
 function setInfo($a){
  if(!$this->_ID) return false;
  
  if(!empty($a['byear']) || !empty($a['bmonth']) || !empty($a['byear'])){
   $a['birthday'] = $a['byear'].'-'.$a['bmonth'].'-'.$a['byear'];
   unset($a['byear'], $a['bmonth'], $a['bday']);
  }
  
  $this->_db->query('UPDATE users_info SET ?a WHERE id = ?d', $a, $this->_ID);
 }
  
  

}