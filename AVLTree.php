<?php

/**
* Node of the tree
*/
class Node {

	public $Left;
	public $Right;
	public $value;
	public $Parent;
	public $height;

	public function __construct ($value, $Parent = null, $Left = null, $Right = null, $height = 0) {
		$this->value = $value;
		$this->Left = $Left;
		$this->Right = $Right;
		$this->Parent = $Parent;
		$this->height = $height;
	}

}

class AVLTree {
	
	private $Root = null;
	private $numNodes = 0;

	/**
	* Pushes the passed in value in the correct place in the tree
	* @param string|int $value
	*/
	public function push ($value) {
		if (!$this->Root) {
			$this->Root = new Node($value);
			$this->numNodes++;
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
			$this->numNodes++;
		} else if ($value < $Node->value && $Node->Left) {
			$this->pushWrapped($value, $Node->Left);
		} else if ($value < $Node->value) {
			$Node->Left = $this->createLeaf($value, $Node);
			$this->numNodes++;
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
		$this->numNodes--;
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
		return $this->numNodes;
	}
	
	/**
	* Given a recently added node it goes through the tree starting from that node 
	* and makes sure it is still balanced 
	* @param Node $Node
	*/
	private function balanceAfterPush ($Node) {
		if (!$Node) { 
			return; 
		}
		if ($Node->Left && $Node->Right) {
			if ($Node->Left->height == $Node->heigth || $Node->Right->height == $Node->height) {
				$Node->height++;
			}
			if ($Node->Left->height - $Node->Right->height > 1) {
				
			}
			$this->balanceAfterPush($Node->Parent);
		} else if ($Node->Left && !$Node->Right) {
			if ($Node->Left->height == $Node->heigth) {
				$Node->height++;
			}
			if ($Node->Left->height > 0) {
				
			}
			$this->balanceAfterPush($Node->Parent);
		} else if (!$Node->Left && $Node->Right) {
			if ($Node->Right->height == $Node->heigth) {
				$Node->height++;
			}
			if ($Node->Right->height > 0) {
				
			}
			$this->balanceAfterPush($Node->Parent);
		} else {
			$Node->height++;
			$this->balanceAfterPush($Node->Parent);
		}
	}
	
}


 ?>
