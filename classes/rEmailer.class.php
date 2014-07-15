<?php


class rEmailer{
	/**
	* Отправить Email
	* @param mixed $to
	* @param mixed $subj
	* @param mixed $template
	* @param bool $from
	* @return void
	*/
	static public function sendEmail($to, $subj, $text, $from = false){
		
		if(!$from)
			$from = ucwords($_SERVER['HTTP_HOST']).' <noreply@'.$_SERVER['HTTP_HOST'].'>';
		
		
		return mail($to, 
				'=?UTF-8?B?'.base64_encode($subj).'?=', 
				$text, 
				"From: $from\r\nContent-Type: text/html; charset=utf-8", 
				"-f$from");
	}
}