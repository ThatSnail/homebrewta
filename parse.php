<?php

class Parse
{
	public static function removeHomebrew($user, $message)
	{
		if($user != "HomebrewTA" or strpos($message, "@HomebrewTA") == 0)
		{
			$message = str_replace("@HomebrewTA", "", $message);
		}
		$message = str_replace("@HomebrewTA", "<a href='http://twitter.com/HomebrewTA'>@HomebrewTA</a>", $message);
		return $message;
	}
}

?>