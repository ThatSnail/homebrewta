<?php

class Parse
{
	private $PARAMS = array("N", "E", "S", "W");

	public static function removeHomebrew($user, $message)
	{
		if($user != "HomebrewTA" or strpos($message, "@HomebrewTA") == 0)
		{
			$message = str_replace("@HomebrewTA", "", $message);
		}
		$message = str_replace("@HomebrewTA", "<a href='http://twitter.com/HomebrewTA'>@HomebrewTA</a>", $message);
		return $message;
	}
	
	public static function getParams($message)
	{
		$params = array();
		$words = explode(" ", $message);
		for($i = 0; $i < count($words) - 1; $i++)
		{
			$word = $words[$i];
			// Possible parameters
			if(in_array("-" . $word, $PARAMS))
			{
				// $word is a parameter
				array_push($params, array($word, $words[$i + 1]));
			}
		}
		return $params;
	}
}

?>