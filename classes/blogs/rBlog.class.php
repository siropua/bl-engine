<?php

class rBlog{

	protected $db_prefix = '';

	protected $app;
	protected $db;

	protected $data;

	public function __construct($app)
	{
		$this->app = $app;
		$this->db = $app->db; // для кратости обращения
	}

	public function selectByID($id)
	{
		
	}

	public function selectDefault()
	{
		$this->data = $this->db->selectRow('SELECT * FROM ?# ORDER BY is_default DESC LIMIT 1', $db_prefix.'blogs');
	}
}