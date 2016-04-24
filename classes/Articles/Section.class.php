<?php

namespace Articles;

trait Section{
	protected $article = NULL;
	public function getArticle()
	{
		if(!$this->article)
			$this->article = Article::get($this->article_id);

		return $this->article;
	}

	static public function getNewN($article_id)
	{
		return \ble\DB::getInstance()->selectCell('SELECT MAX(order_n) FROM articles_sections WHERE article_id = ?d', $article_id) + 1;
	}
}