<?php

/**
* Класс, который зело помогает нам парсить всякие страницы
* В качестве браузера используется rLib-овский ViBrowser
* А парсим полученный контент с помощью DOMXPath
*/
class rXpathHelper
{
	
	protected $content = '';
	protected $vb = NULL;
	protected $parentNode = NULL;
	protected $doc = NULL;
	protected $xpath = NULL;

	function __construct()
	{
		require_once 'rlib/vibrowser.inc.php';
		$this->vb = new ViBrowser;
		$this->vb->setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.45 Safari/537.36');

		libxml_use_internal_errors(true);

		$this->doc = new DOMDocument();
	}

	public function loadURL($url, $referer = false)
	{
		if(!$referer) $referer = $url;

		$this->vb->setURL($referer);
		return $this->setHTML($this->vb->get($url));
	}

	public function GET($url)
	{
		return $this->vb->get($url);
	}

	public function setHTML($html)
	{
		$this->content = $html;
		$this->parentNode = NULL;

		
		$this->doc->loadHTML($this->content);

		unset($this->xpath);
		$this->xpath = new DOMXpath($this->doc);
		return $this;
	}

	public function query($str, $parent = null)
	{
		return $this->xpath->query($str, $parent);
	}

	/**
	* Устанавливает элемент, от которого будут считаться все запросы
	* По сути равно $('query', PARENT)
	*/
	public function setParent($DOMElement)
	{
		$this->parentNode = $DOMElement;
		return $this;
	}

	public function clearParent()
	{
		$this->setParent(NULL);
		return $this;
	}

	public function getValue($query, $parent = NULL)
	{
		$parent = $parent ? $parent : $this->parentNode;

		$result = $this->xpath->query($query, $parent);
		if(!$result->length) return NULL;

		return $result->item(0)->nodeValue;
	}

	public function getStr($query, $parent = NULL)
	{
		return trim($this->getValue($query, $parent));
	}

	public function getFloat($query, $parent = NULL)
	{
	

		$float = $this->getStr($query, $parent);
		$float = str_replace(',', '.', $float); 
		$float = preg_replace('~[^0-9.]~', '', $float);
		$float = floatval($float);

		return $float;
	}

	public function vb()
	{
		return $this->vb;
	}
}