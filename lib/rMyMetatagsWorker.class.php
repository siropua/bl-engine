<?php

class rMyMetatagsWorker{

	static public function getDescription($string, 
			$length = 190, 
			$etc = '...',
            $charset='UTF-8',
            $break_words = false,
            $middle = false)
	{

		$string = trim(preg_replace("~<(p|div[^>]+)>~i", ' <$1>', $string));
		$string = strip_tags($string);
		$string = trim(preg_replace('~\s+~', ' ', $string));
		if(!$string) return '';

		if (!$length) return '';
  
	    if (self::strlen($string) > $length) {
	        $length -= min($length, self::strlen($etc));
	        if (!$break_words && !$middle) {
	            $string = preg_replace('/\s+?(\S+)?$/', '', 
	                             mb_substr($string, 0, $length+1, $charset));
	        }
	        if(!$middle) {
	            return htmlspecialchars(mb_substr($string, 0, $length, $charset) . $etc);
	        } else {
	            return htmlspecialchars(mb_substr($string, 0, $length/2, $charset) . 
	                             $etc . 
	                             mb_substr($string, -$length/2, $charset));
	        }
	    } else {
	        return htmlspecialchars($string);
	    }
	}

	static public function strlen($str)
	{
		return mb_strlen($str, 'UTF-8');
	}
}
