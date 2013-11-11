<?php
class Tweet
{
	public static function getMessage($tweet)
	{
		return $tweet['text'];
	}
	public static function getUser($tweet)
	{
		return $tweet['user']['screen_name'];
	}
}
?>