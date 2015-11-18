<?php

namespace APP\ShipsBundle\Utils;

/**
* Ship 
*
* @param integer $size
* @param string $direction
* @return object Block
*/
class Ship
{
	private $size;
	private $direction;
	private $id;

	function __construct($size, $direction)
	{
		$this->size = $size;
		$this->direction = $direction;
		$this->id = uniqid();
	}

	public function getSize()
	{
		return $this->size;
	}

	public function getDirection()
	{
		return $this->direction;
	}

	public function getId()
	{
		return $this->id;
	}
}