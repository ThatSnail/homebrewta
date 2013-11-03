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

if($get_tweet != "")
{
	$current_tweet = $get_tweet;
}
else
{
	$current_tweet = 0;
}

$tweets = $_SESSION['data'];

Random::seed(105);

class Grid
{
	private $grid = Array();
	private $neighbors = Array();
	
	function has($x, $y)
	{
		if(isset($this->grid[$x]))
		{
			if(isset($this->grid[$x][$y]))
			{
				return true;
			}
		}
		return false;
	}
	function set($x, $y, $value)
	{
		if(!isset($this->grid[$x]))
		{
			$this->grid[$x] = Array();
		}
		$this->grid[$x][$y] = $value;
		
		// Remove self from neighbors
		$pos = array_search(array($x, $y), $this->neighbors);
		if(pos !== false)
		{
			unset($this->neighbors[$pos]);
		}
		
		// Add neighbors
		if(!$this->has($x - 1, $y) && !in_array(array($x - 1, $y), $this->neighbors))
		{
			array_push($this->neighbors, array($x - 1, $y));
		}
		if(!$this->has($x + 1, $y) && !in_array(array($x + 1, $y), $this->neighbors))
		{
			array_push($this->neighbors, array($x + 1, $y));
		}
		if(!$this->has($x, $y - 1) && !in_array(array($x, $y - 1), $this->neighbors))
		{
			array_push($this->neighbors, array($x, $y - 1));
		}
		if(!$this->has($x, $y + 1) && !in_array(array($x, $y + 1), $this->neighbors))
		{
			array_push($this->neighbors, array($x, $y + 1));
		}
		
		$this->neighbors = array_values($this->neighbors);
	}
	function get($x, $y)
	{
		return $this->grid[$x][$y];
	}
	function setRandomNeighbor($value)
	{
		// Find a random neighbor and set it there
		$n = $this->neighbors[Random::num(0, count($this->neighbors) - 1)];
		$this->set($n[0], $n[1], $value);
		return array($n[0], $n[1]);
	}
}

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

$current_message = $tweets[$current_tweet]['text'];
$current_user = $tweets[$current_tweet]['user']['screen_name'];
if($current_user != "HomebrewTA" or strpos($current_message, "@HomebrewTA") == 0)
{
	$current_message = str_replace("@HomebrewTA", "", $current_message);
}
$current_message = str_replace("@HomebrewTA", "<a href='http://twitter.com/HomebrewTA'>@HomebrewTA</a>", $current_message);

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