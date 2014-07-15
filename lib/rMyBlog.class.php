<?php
require_once('rlib/blog/rBlog.class.php');
require_once 'rlib/blog/jevixParser.class.php';

class rMyBlog extends rBlog{
	

	public function __construct($site)
	{
		parent::__construct($site, array(
			'textParserClass' => 'jevixParser',
		));
	}

}