<?php



namespace ble;

if(!class_exists('Config_File'))
        require_once("rlib/quicky/Config_File.class.php");

/**
* Абстрактный класс роутера
*/
class Dates
{
	protected $config = NULL;
	protected static $instance = NULL;

	public $days_names=array(-2=>'позавчера', -1=>"вчера", 0=>"сегодня", 1=>"завтра", 2=>"послезавтра");

	public $monthNames=array(1=>"января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");

	public $shortMonthNames=array(1=>"янв", "фев", "мар", "апр", "май", "июн", "июл", "авг", "сен", "окт", "ноя", "дек");

	public $weekNames=array("Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье");

	public $shortWeekNames=array("Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс");

	protected $show2DayPhrase = false;

	protected function __construct()
	{
		$this->config = new \Config_File();
	}

	public function loadLangFile($langCode)
	{
		$file = ENGINE_PATH.'/locales/'.$langCode.'.txt';
		// echo $file; exit;
		if(!file_exists($file)) return false;

		$dates = $this->config->get($file, 'dates');
		if(!$dates) return false;

		$this->days_names = array(
			-2 => $dates['dby'],
			-1 => $dates['yesterday'],
			0 => $dates['today'],
			1 => $dates['tomorrow'],
			2 => $dates['dat'],
		);

		$this->monthNames = array(
			1 => $dates['january'], $dates['february'], $dates['march'], $dates['april'], $dates['may'], $dates['june'], 
				$dates['july'], $dates['august'], $dates['september'], $dates['october'], $dates['november'], $dates['december'], 
		);

		$this->shortMonthNames = array(
			1 => $dates['jan'], $dates['feb'], $dates['mar'], $dates['apr'], $dates['may'], $dates['jun'], 
				$dates['jul'], $dates['aug'], $dates['sep'], $dates['oct'], $dates['nov'], $dates['dec'], 
		);

		$this->weekNames = array(
			$dates['sunday'], $dates['monday'], $dates['tuesday'], $dates['wednesday'], $dates['thusday'], $dates['friday'], 
				$dates['saturday'], $dates['sunday'], 
		);

		$this->shortWeekNames = array(
			$dates['su'], $dates['mo'], $dates['tu'], $dates['we'], $dates['th'], $dates['fr'], 
				$dates['sa'], $dates['su'], 
		);

		return true;
	}


	public function formatDateTime($date = 0, $params = array())
	{
	
		
		$date=(int)$date;
		if(!$date)$date=time();
		$date_str = date("j n Y", $date);
		list($dD, $dM, $dY)=explode(" ", $date_str);
		list($curD, $curM, $curY) = explode(" ", date("j n Y"));

		$time_str = date("H:i".(@$params['show_seconds'] ? ":s":""), $date);
		
		$days = (mktime(0, 0, 0, $dM, $dD, $dY) - mktime(0, 0, 0, $curM, $curD, $curY)) / (60*60*24) ;
		if(abs($days) < ($this->show2DayPhrase ? 3 : 2))
		{
			return $this->days_names[$days].", $time_str";
		}

		$ret="";
		
		if(@!$params['hide_dayname'])
			$ret = $this->shortWeekNames[date('w', $date)].", ";
		$ret .= $dD.' '.$this->monthNames[$dM];
		
		if($dY != $curY)
			$ret .= ' '.$dY;
		
		$ret .= ', '.$time_str;

		return trim($ret);
	}

	public function formatSeconds($seconds = 0)
	{
		$s = $seconds % 60;
		$seconds = floor($seconds / 60);
		$m = $seconds % 60;
		$seconds = floor($seconds / 60);
		$h = $seconds % 24;
		$d = floor($seconds / 24);
		$ret = '';
		$ret .= $d > 0 ? $d.' ' : '';
	}

	public static function getInstance()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}