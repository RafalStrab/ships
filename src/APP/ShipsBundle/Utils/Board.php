<?php

namespace APP\ShipsBundle\Utils;

/**
* Board
*
* @param integer $width
* @param string $height
* @return object Board
*/
class Board
{
	private $width;
	private $height;

	function __construct($width, $height)
	{
		$this->width = $width;
		$this->height = $height;

		$this->board = array();
		for ($i=0; $i < $this->width; $i++) { 
			$this->board[$i] = array();
			for ($y=0; $y < $this->height; $y++) { 
				array_push($this->board[$i], ".");
			}
		}

	}

	/**
	* Add ship to the board
	*/
	public function addShip($ship)
	{
		$startX = $this->getStartX();
		$startY = $this->getStartY();

		$isFree = $this->isFree($startX, $startY, $ship);

		while (!$isFree) {
			$startX = $this->getStartX();
			$startY = $this->getStartY();
			$isFree = $this->isFree($startX, $startY, $ship);
		}

		$this->addShipToBoard($startX, $startY, $ship);

		return $this->board;
	}

	/**
	* Get X coord random start position
	*/
	private function getStartX()
	{
		return rand(0, $this->width - 1);
	}

	/**
	* Get Y coord random start position
	*/
	private function getStartY()
	{
		return rand(0, $this->height - 1);
	}

	/**
	* Place block to the board
	*/
	private function addShipToBoard($startX, $startY, $ship)
	{
		// Decrease shipSize for placement calculation
		$shipSize = $ship->getSize() - 1;

		// Start position correction so block won't be placed outside the board
		while (($this->width - $startX) < $shipSize) {
			$startX = $this->getStartX();
		}

		// Start position correction so block won't be placed outside the board
		while (($this->width - $startY) < $shipSize) {
			$startX = $this->getStartY();
		}

		// Coords correction so it won't be < 0
		if ($startX > 0) {
			$startX -= 1;
		}

		// Coords correction so it won't be < 0
		if ($startY > 0) {
			$startY -= 1;
		}

		for ($i = $startX; $i <= ($startX + $shipSize); $i++) { 
			if ($ship->getDirection() == 'horizontal') {
				$this->board[$startX][$i] = $ship->getId();
			} elseif ($ship->getDirection() == 'vertical' && ($this->height - $startY) >= $shipSize) {
				$this->board[$i][$startY] = $ship->getId();
			}
			
		}
	}

	/**
	* Check if XY position is free to place a block
	*/
	private function isFree($startX, $startY, $ship)
	{
		$position = $this->board[$startX][$startY];

		if ($position == '.') {
			switch ($ship->getDirection()) {
				case 'vertical':
					if ($ship->getSize() <= ($this->width-$startX)) {
						return true;
					}
					break;
				case 'horizontal':
					if ($ship->getSize() <= ($this->height - $startY)) {
						return true;
					}
					break;
			}
		}

		return false;
	}
}