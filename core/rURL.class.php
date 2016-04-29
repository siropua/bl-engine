<?php

namespace ble;

require_once 'classes/rURLParser.class.php';

/**
* Работа с УРЛами 
*/
class rURL extends \model_urls
{
	static public function createURL($url, $base = '', $handler = '', $handledID = null)
	{
		$uniqURL = self::getUniqURL($url, $base);

		return self::create([
			'valid_from' => time(),
			'date_add' => time(),
			'url' => $uniqURL,
			'url_hash' => self::hashURL($uniqURL),
			// 'url_type' => $urlParser->getType() == \rURLParser::URLTYPE_FILE ? 'file' : 'folder',
			'handler' => $handler,
			'handled_id' => $handledID
		]);
	}

	static public function getUniqURL($url, $base = '', $currentID = NULL)
	{
		$urlParser = new \rURLParser($url, $base);
		$fineURL = $urlParser->finePath();

		return self::searchURL($fineURL, $currentID);
	}

	public function setHandler($handler, $handleID = NULL)
	{
		$this->setFields(['handler' => trim($handler), 'handled_id' => $handleID], true);
		return $this;
	}

	static public function searchURL($url, $currentID = null)
	{
		if(!$url) $url = 'url';
		$urlNew = $url;
		$i = 0;
		while(DB::getInstance()->selectCell('SELECT id FROM urls WHERE url_hash = ?{ AND id <> ?d}', $urlNew, $currentID ? $currentID : DBSIMPLE_SKIP))
			$urlNew = $url .'-'.(++$i);
		return $urlNew;
	}

	static public function hashURL($url)
	{
		return md5($url);
	}

	static public function getHandled($handler, $handledID)
	{
		return self::get(['handler' => $handler, 'handled_id' => $handledID]);
	}

	static public function deleteHandled($handler, $handledID)
	{
		DB::getInstance()->query('DELETE FROM urls WHERE handled_id = ?d AND handler = ?', $handledID, $handler);
	}

	public function updateURL($newURL)
	{
		$uniqURL = $this->getUniqURL($newURL, false, $this->id);
		$this->setFields(['url' => $uniqURL, 'url_hash' => $this->hashURL($uniqURL)]);

		return $this->save();
	}

	static public function getURL($url)
	{
		if(!$urlParser = \rURLParser::fromURI($url)) return false;
		return self::get(['url_hash' => self::hashURL($urlParser->safePath())]);
	}
}