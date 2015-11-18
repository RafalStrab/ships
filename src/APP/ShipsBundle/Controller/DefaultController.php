<?php

namespace APP\ShipsBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use APP\ShipsBundle\Utils\Board;
use APP\ShipsBundle\Utils\Ship;

/**
 * @Route("/")
 */
class DefaultController extends Controller
{
    /**
     * Main page
     * 
     * @Route("/{debug}", name="index")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request, $debug = null)
    {
    	$session = $request->getSession();
    	$coord = $request->request->get('coord');
    	
    	$row = $this->getRow($coord);
    	$col = $this->getCol($coord);

    	$success = true;
		$message = null;

    	if ($request->getMethod() == 'GET') {
	    	$board = new Board(10,10);
			$ship1 = new Ship(5, "horizontal");
			$ship2 = new Ship(4, "horizontal");
			$ship3 = new Ship(4, "vertical");

			$boardUser = $board->board;

			$s1 = $board->addShip($ship1);
			$s2 = $board->addShip($ship2);
			$s3 = $board->addShip($ship3);
			
			// Array for User Interface
			$session->set('boardUser', $boardUser);
			// Array with all ships placed on the board
			$session->set('boardRaw', $s3);
			// Hits left
			$shotsTotal = 0;
			$session->set('shotsTotal', $shotsTotal);
			// Shots total
			$hitsLeft = 13;
			$session->set('hitsLeft', $hitsLeft);
    	} else {
    		$shotsTotal = $session->get('shotsTotal');
    		$shotsTotal++;
    		$session->set('shotsTotal', $shotsTotal);

    		$hitsLeft = $session->get('hitsLeft');

    		if (!$row || !$col) {
    			$success = false;
				$message = 'Error. Incorrect row and/or column.';
    		} else {
	    		$boardUser = $session->get('boardUser');
	    		$boardRaw = $session->get('boardRaw');

	    		$cellValue = $boardRaw[$row-1][$col-1];
	    		
	    		if ($cellValue == '.' || $cellValue == '--') {
	    			$boardUser[$row - 1][$col - 1] = '--';
	    		} else {
	    			$boardUser[$row - 1][$col - 1] = 'X';
	    			$hitsLeft -= 1;
	    			$session->set('hitsLeft', $hitsLeft);
	    		}

	    		$session->set('boardUser', $boardUser);
    		}
    	}
    	return array(
    		'success' 	   => $success,
    		'message'      => $message,
    		'shotsTotal'   => $shotsTotal,
    		'hitsLeft'     => $hitsLeft,
    		'debug'   	   => $debug,
    	);
    }

    /**
	* Extract row from $coord
	* 
	* @param string $coord
	* @return integer $row
    */
    private function getRow($coord)
    {
    	$row = intval(substr($coord, 1,2));

    	if (!is_integer($row) || $row > 10) {
    		return false;
    	}

    	return $row;
    }

    /**
	* Extract column from $coord
	* 
	* @param string $coord
	* @return integer $column
    */
    private function getCol($coord)
    {
    	$col = strtoupper(substr($coord, 0,1));

    	// Available columns
    	$cols = array("A","B","C","D","E","F","G","H","I","J");

    	if (!in_array($col, $cols)) {
    		return false;
    	}

    	return array_search($col, $cols) + 1;
    }
}
