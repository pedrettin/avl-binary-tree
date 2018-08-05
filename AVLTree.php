<?php

/**
* Node of the tree
*/
class Node {

	public $Left;
	public $Right;
	public $value;
	public $Parent;

	public function __construct ($value, $Parent = null, $Left = null, $Right = null) {
		$this->value = $value;
		$this->Left = $Left;
		$this->Right = $Right;
		$this->Parent = $Parent;
	}
	
	public function __toString () {
		$nodeToString = '';
		$nodeToString .= "  " . $this->value . "\n";
		$nodeToString .= ($this->Left ? " /" : " ") . "  " . ($this->Right ? "\\" : "") . "\n";
		$leftNode = ($this->Left ? $this->Left->value : "");
		$rightNode = ($this->Right ? $this->Right->value : "");
		return 	$nodeToString . $leftNode . "    " . $rightNode . "\n\n";
	}

}

class AVLTree {
	
	private $Root = null;

	/**
	* Pushes the passed in value in the correct place in the tree
	* @param string|int $value
	*/
	public function push ($value) {
		if (!$this->Root) {
			$this->Root = new Node($value);
		} else {
			$this->pushWrapped($value, $this->Root);
		}
	}
	
	/**
	* Pushes the passed in value in the correct place in the tree -- making sure it 
	* remains balanced -- given the node to start from.
	* @param string|int $value
	* @param Node $Node
	*/
	private function pushWrapped ($value, $Node) {
		if ($value > $Node->value && $Node->Right) {
			$this->pushWrapped($value, $Node->Right);
		} else if ($value > $Node->value) {
			$Node->Right = $this->createLeaf($value, $Node);
			$this->balanceTree($Node->Right);
		} else if ($value < $Node->value && $Node->Left) {
			$this->pushWrapped($value, $Node->Left);
		} else if ($value < $Node->value) {
			$Node->Left = $this->createLeaf($value, $Node);
			$this->balanceTree($Node->Left);
		}
	}
	
	/**
	* Given a new node it checks to see if the tree needs to be balanced and
	* performs a rotation if necessary.
	* @param Node $NewNode
	*/
	private function balanceTree ($NewNode) {
		$Rotation = $this->determineRotation($NewNode);
		if ($Rotation == "LL") {
			$this->rotateLeft($NewNode->Parent);
		} else if ($Rotation == "RR") {
			$this->rotateRight($NewNode->Parent);
		} else if ($Rotation == "LR") {
			$this->rotateLeft($NewNode);
			$this->rotateRight($NewNode);
		} else if ($Rotation == "RL") {
			$this->rotateRight($NewNode);
			$this->rotateLeft($NewNode);
		}
	}
	
	/**
	* Determines which rotation needs to be performed (if any) to balance the tree.
	* The first time this method is called it should be passed in the newly inserted node
	* @param Node $Node 
	* @param string $Rotation
	* @return string|boolean returns a string indicating which rotation is needed 
	* "LL" - left rotation
	* "RR" - right rotation
	* "LR" - left right rotation
	* "RL" - right left rotation
	* false is returned if no rotation is needed
	*/
	private function determineRotation ($Node, $Rotation = '') {
		$Parent = $Node->Parent;
		if (strlen($Rotation) == 2) { return $Rotation; }
		if (!$Parent || ($Parent->Right && $Parent->Left)) { return false; }
		if ($Node->value < $Node->Parent->value) { 
			$Rotation .= "R";
		} else {
			$Rotation .= "L";
		}
		return $this->determineRotation($Node->Parent, $Rotation);
	}
	
	/**
	* Given the middle node it performs a left rotation
	* @param Node $Node middle node
	*/
	private function rotateLeft ($Node) {
		$Node->Left = $Node->Parent;
		$Node->Parent->Right = null;
		if ($Node->Parent === $this->Root) {
			$this->Root = $Node;
		}
		$Node->Parent = $Node->Parent->Parent;
		if ($Node->Parent && $Node->Parent->value > $Node->value) {
			$Node->Parent->Left = $Node;
		} else if ($Node->Parent) {
			$Node->Parent->Right = $Node;
		}
	}
	
	/**
	* Given the middle node it performs a right rotation
	* @param Node $Node middle node
	*/
	private function rotateRight ($Node) {
		$Node->Right = $Node->Parent;
		$Node->Parent->Left = null;
		if ($Node->Parent === $this->Root) {
			$this->Root = $Node;
		}
		$Node->Parent = $Node->Parent->Parent;
		if ($Node->Parent && $Node->Parent->value > $Node->value) {
			$Node->Parent->Left = $Node;
		} else if ($Node->Parent) {
			$Node->Parent->Right = $Node;
		}
	}
	
	/**
	* Creates a new node with the passed in value and returns it
	* @param string|int $value
	* @param Node $Parent
	* @return Node
	*/
	private function createLeaf ($value, $Parent) {
		return new Node ($value, $Parent);
	}

	/**
	* Checks if $value is present or not in the tree
	* @return boolean
	*/
	public function find ($value) {
		if (!$this->Root) { return false; }
		else {
			// double exclamation point to turn value into boolean
			return !! $this->findWrapped($value, $this->Root);
		}
	}

	/**
	* Checks if $value is present or not in the tree starting from $Node
	* @param string|int $value
	* @param Node $Node
	*/
	public function findWrapped ($value, $Node) {
		if ($Node->value == $value) {
			return $Node;
		} else if ($Node->value < $value && $Node->Right) {
			return $this->findWrapped($value, $Node->Right);
		} else if ($Node->value > $value && $Node->Left) {
			return $this->findWrapped($value, $Node->Left);
		} else {
			return false;
		}
	}

	/**
	* Removes node with $value from the tree
	* @param string|int $value
	*/
	public function remove ($value) {
		if ($this->Root) {
			$this->removeWrapped($value, $this->Root);
		}
	}

	/**
	* Removes node with $value from tree starting from $Node
	* @param string|int $value
	* @param Node $Node
	*/
	public function removeWrapped ($value, $Node) {
		$NodeToRemove = $this->findWrapped($value, $Node);
		if (!$NodeToRemove) { return; }
		if (!($NodeToRemove->Right && $NodeToRemove->Left)) {
			$this->removeNoOrOneChildren($NodeToRemove->Parent, $NodeToRemove);
		} else {
			$this->removeTwoChildren($NodeToRemove);
		}
	}

	/**
	* Removes a node that has 0 or 1 children
	* It does not work for nodes that have 2 children
	* @param Node $Parent
	* @param Node $NodeToRemove
	*/
	private function removeNoOrOneChildren ($Parent, $NodeToRemove) {
		$NodeToAssign = $NodeToRemove->Right ? $NodeToRemove->Right : $NodeToRemove->Left;
		if ($Parent->value > $NodeToRemove->value) {
			$Parent->Left = $NodeToAssign;
		} else {
			$Parent->Right = $NodeToAssign;
		}
	}

	/**
	* Removes a node that has 2 children
	* @param Node $Parent
	* @param Node $NodeToRemove
	*/
	private function removeTwoChildren ($NodeToRemove) {
		$NodeToRemove->value = $NodeToRemove->Left->value;
		$LeftNodeRightBranch = $NodeToRemove->Left->Right;
		$NodeToRemove->Left = $NodeToRemove->Left->Left;
		$SmallestChildRightBranchNodeToRemove = $this->findLeftMostChild($NodeToRemove);
		$SmallestChildRightBranchNodeToRemove->Left = $LeftNodeRightBranch;
	}

	/**
	* Finds the left most child -- smallest -- in a tree
	* @param Node $Node
	* @return Node
	*/
	private function findLeftMostChild ($Node) {
		if (!$Node->Left) {
			return $Node;
		}
		return $this->findLeftMostChild($Node->Left);
	}

	/**
	* Returns the current number of nodes in the tree
	* @return int
	*/
	public function getNumNodes () {
		if (!$this->Root) { return 0; } 
		return $this->getNumNodesWrapped($this->Root);
	}
	
	/**
	* Recursively counts the number of nodes in the tree
	* @param Node $Node - node currently being analyzed
	* @return int
	*/
	private function getNumNodesWrapped ($Node) {
		$numNodes = 1;
		if (!$Node->Left && !$Node->Right) {return $numNodes;}
		if ($Node->Right) {
			$numNodes += $this->getNumNodesWrapped($Node->Right);
		}
		if ($Node->Left) {
			$numNodes += $this->getNumNodesWrapped($Node->Left);
		}
		return $numNodes;
	}
	
}


 ?>
