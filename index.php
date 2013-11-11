<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<link href="stylesheet.css?<?php echo date('l jS \of F Y h:i:s A') ?>" rel="stylesheet" type="text/css">
<script language="javascript">
function move(e)
{
	var event = window.event ? window.event : e;
	switch(event.keyCode)
	{
		case 87: // W
			window.location = "?tweet=" + document.getElementById("key_w").value;
		break;
		case 83: // S
			window.location = "?tweet=" + document.getElementById("key_s").value;
		break;
		case 65: // A
			window.location = "?tweet=" + document.getElementById("key_a").value;
		break;
		case 68: // D
			window.location = "?tweet=" + document.getElementById("key_d").value;
		break;
	}
}

document.onkeydown = move;
</script>
</head>
<title>
Make Your Own Text Adventure!
</title>
<body>
<p class="bodytext">!!! Under construction, expect magic things to happen !!!</p>
<?php
$get_tweet = filter_var($_GET['tweet'], FILTER_SANITIZE_STRING);

if($get_tweet == 0)
{
	unset($_SESSION['data']);
}

if(!isset($_SESSION['data']))
{
	$data = file_get_contents("data.json");
	$_SESSION['data'] = array_reverse(json_decode($data, true));
}

require_once("./random.php");
require_once("./grid.php");
require_once("./tweet.php");
require_once("./parse.php");

if($get_tweet != ""){$current_tweet = $get_tweet;}else{$current_tweet = 0;}
$tweets = $_SESSION['data'];
Random::seed(105);

$current_x = 0;
$current_y = 0;

$grid = new Grid();
for ($i = 0; $i < count($tweets); $i++)
{
	// If first, place at (0, 0)
	if($i == 0)
	{
		$grid->set(0, 0, $i);
		$x = 0;
		$y = 0;
	}
	else
	{
		$n = $grid->setRandomNeighbor($i);
		$x = $n[0];
		$y = $n[1];
	}
	
	if($i == $current_tweet)
	{
		$current_x = $x;
		$current_y = $y;
	}
}

$tweet = $tweets[$current_tweet];
$current_message = Tweet::getMessage($tweet);
$current_user = Tweet::getUser($tweet);
$current_message = Parse::removeHomebrew($current_user, $current_message);

// Print
{
	echo "<p class='message'>" . $current_message . "</p><br>";
	if($grid->has($current_x, $current_y - 1))
	{
		echo "<p class='key'>W - Go north</p>";
		echo "<input id='key_w' type='hidden' value='" . $grid->get($current_x, $current_y - 1) . "' />";
	}
	if($grid->has($current_x, $current_y + 1))
	{
		echo "<p class='key'>S - Go south</p>";
		echo "<input id='key_s' type='hidden' value='" . $grid->get($current_x, $current_y + 1) . "' />";
	}
	if($grid->has($current_x - 1, $current_y))
	{
		echo "<p class='key'>A - Go west</p>";
		echo "<input id='key_a' type='hidden' value='" . $grid->get($current_x - 1, $current_y) . "' />";
	}
	if($grid->has($current_x + 1, $current_y))
	{
		echo "<p class='key'>D - Go east</p>";
		echo "<input id='key_d' type='hidden' value='" . $grid->get($current_x + 1, $current_y) . "' />";
	}
	echo "<p class='user'><i>Posted by:</i> <a href='http://twitter.com/" . $current_user . "'>@" . $current_user . "</a></p>";
}
?>
<p class="wat"><a href="./wat.php">wat.php</a></p>
</body>
</html>