<?php

namespace Datatype;

/**
* Работа с периодами дат
*/
class Daterange
{
	
	protected $from;
	protected $to;

	function __construct(\DateTime $from, \DateTime $to)
	{
		$this->from = $from;
		$this->to = $to;
	}

	static public function fromString($range, $format = 'm/d/Y', $delimeter = ' - ')
	{
		if(!strpos($range, $delimeter)) return false;
		
		list($from, $to) = explode($delimeter, $range, 2);
		$from = \DateTime::createFromFormat($format, trim($from));
		$to = \DateTime::createFromFormat($format, trim($to));

		return new self($from, $to);
	}

	public function from()
	{
		return $this->from;
	}

	public function to()
	{
		return $this->to;
	}

	public function days()
	{
		return $this->from->diff($this->to, true)->format('%a') + 1;
	}
}