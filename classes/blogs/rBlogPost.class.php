<?php

if(!defined('rb_POST_TABLE')) define('rb_POST_TABLE', 'blog_posts');

class rBlogPost{

	static protected $postTable = 'blog_posts';


	static public function post($app, $p)
	{

		echo 'aaaa';

		$p = self::filterArray($p, array('url', 'blog_id', 'visible', 'allow_comments', 'title', 'preview', 'text', 'source_url', 'status', 'geo_lat', 'geo_lng', 'post_mode'));

		print_r($p);

		exit;

		$app->db->query('INSERT INTO ?# SET ?a', self::$postTable, $p);
	}

	static public function filterArray($arr, $str)
	{
		if(!is_array($str)) $str = explode(',', $str);
		return array_intersect_key($arr, array_flip($str));
	}

}