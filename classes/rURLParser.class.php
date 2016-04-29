<?php


/**
* Парсер урлов и обработка их
*/
class rURLParser
{
	protected 
		$rawURL = '',
		$safeURL = '',
		$latinURL = '',
		$URLType = 0,
		$fileType = '';

	const 
		URLTYPE_UNK = 0,
		URLTYPE_FILE = 1,
		URLTYPE_DIR = 2;

	function __construct($url, $base = '', $ignorePart = false)
	{
		$this->setURL($url, $base, $ignorePart);
	}

	static public function fromURI($uri, $base = '', $ignorePart = false)
	{
		if(($pos = strpos($uri, '?')) !== false)
			$uri = substr($uri, 0, $pos);
		return new self($uri, $base, $ignorePart);
	}

	public function setURL($url, $base, $ignorePart)
	{
		$url = trim($url, ' /\\');
		$base = trim($base, ' /\\');
		$ignorePart = trim($ignorePart, ' /\\');
		$this->rawURL = ($base ? $base .'/' : '').$url;
		
		if($this->rawURL && $ignorePart)
		{
			if(substr($this->rawURL, 0, strlen($ignorePart)) == $ignorePart)
				$this->rawURL = trim(substr($this->rawURL, strlen($ignorePart)), ' /\\');
		}

		$this->URLType = self::URLTYPE_UNK;
		$this->URLType = preg_match('~\\.[a-z0-9]{2,5}$~i', $this->rawURL) ? self::URLTYPE_FILE : self::URLTYPE_DIR;
		
		$this->rawParts = explode('/', preg_replace('~\\.[a-z0-9]{2,5}$~i', '', $this->rawURL));
		$this->parts = array_map([$this, 'clearURLPart'], $this->rawParts);
	}

	public function getType()
	{
		return $this->URLType;
	}

	public function safePath()
	{
		return implode('/', $this->parts);
	}

	public function finePath()
	{
		return implode('/', array_map([$this, 'fineURLPart'], $this->rawParts));
	}

	public function part($n)
	{
		if(empty($this->parts[$n])) return '';
		return $this->parts[$n];
	}

	public function partsCount()
	{
		return count($this->parts);
	}

	protected function parseURL()
	{
		
	}

	static public function clearURLPart($str)
	{
		$str = self::translit($str); 
		$str = preg_replace('~[^a-z0-9]~i', '-', $str);

		return $str;
	}

	static public function fineURLPart($str)
	{
		$str = self::translit($str);
		$str = preg_replace('~["\'`^]~', '', $str);
		$str = preg_replace('~[^a-z0-9]~i', '-', $str);
		$str = trim(preg_replace('~-+~', '-', $str), ' -');

		return $str;
	}

	static public function translit($string)
	{


		$replace=array(
		"'"=>"_",
		"`"=>"_",
		"а"=>"a","А"=>"a",
		"б"=>"b","Б"=>"b",
		"в"=>"v","В"=>"v",
		"г"=>"g","Г"=>"g",
		"д"=>"d","Д"=>"d",
		"е"=>"e","Е"=>"e",
		"ё"=>"e","Ё"=>"e",
		"ж"=>"zh","Ж"=>"zh",
		"з"=>"z","З"=>"z",
		"и"=>"i","И"=>"i",
		"й"=>"y","Й"=>"y",
		"к"=>"k","К"=>"k",
		"л"=>"l","Л"=>"l",
		"м"=>"m","М"=>"m",
		"н"=>"n","Н"=>"n",
		"о"=>"o","О"=>"o",
		"п"=>"p","П"=>"p",
		"р"=>"r","Р"=>"r",
		"с"=>"s","С"=>"s",
		"т"=>"t","Т"=>"t",
		"у"=>"u","У"=>"u",
		"ф"=>"f","Ф"=>"f",
		"х"=>"h","Х"=>"h",
		"ц"=>"c","Ц"=>"c",
		"ч"=>"ch","Ч"=>"ch",
		"ш"=>"sh","Ш"=>"sh",
		"щ"=>"sch","Щ"=>"sch",
		"ъ"=>"","Ъ"=>"",
		"ы"=>"y","Ы"=>"y",
		"ь"=>"","Ь"=>"",
		"э"=>"e","Э"=>"e",
		"ю"=>"yu","Ю"=>"yu",
		"я"=>"ya","Я"=>"ya",
		"і"=>"i","І"=>"i",
		"ї"=>"yi","Ї"=>"yi",
		"є"=>"e","Є"=>"e"
		);
		$str = iconv("UTF-8", "ASCII//TRANSLIT", strtr($string,$replace));
		return $str;

		setlocale(LC_ALL, 'en_US.UTF8');
		$rus = array('ё','ж','ц','ч','ш','щ','ю','я','Ё','Ж','Ц','Ч','Ш','Щ','Ю','Я');
		$lat = array('yo','zh','tc','ch','sh','sh','yu','ya','YO','ZH','TC','CH','SH','SH','YU','YA');
		$string = str_replace($rus,$lat,$string);
		$string = strtr($string,
		     "АБВГДЕЗИЙКЛМНОПРСТУФХЪЫЬЭабвгдезийклмнопрстуфхъыьэ",
		     "ABVGDEZIJKLMNOPRSTUFH_I_Eabvgdezijklmnoprstufh_i_e");
		
		// $string = @iconv('UTF-8', 'ASCII//TRANSLIT', $string);
		return $string;
	}
}