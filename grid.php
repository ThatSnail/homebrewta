<?php

require_once("./random.php");

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

?>