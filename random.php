<?php

class Random
{
	private static $RSeed = 0;
	
	public static function seed($s = 0) {
		self::$RSeed = abs(intval($s)) % 9999999 + 1;
		self::num();
	}
	
	public static function num($min = 0, $max = 9999999) {
		if (self::$RSeed == 0) self::seed(mt_rand());
		self::$RSeed = (self::$RSeed * 125) % 2796203;
		return self::$RSeed % ($max - $min + 1) + $min;
	}
}

?>