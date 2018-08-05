<?php 

include('./AVLTree.php');
use PHPUnit\Framework\TestCase;

/**
* Not the best way to test this since we are pretty much only checking that things
* are still foundable and assume the rotation happened correctly.
*/

class AVLTreeTest extends TestCase {
	
	public function testCreate () {
		$Tree = new AVLTree();
		$this->assertTrue(is_a($Tree, 'AVLTree'));
	}
	
	public function testLeftRotation () {
		$Tree = new AVLTree();
		$Tree->push(10);
		$Tree->push(9);
		$Tree->push(8);
		$this->assertTrue($Tree->getNumNodes() == 3);
		$this->assertTrue($Tree->find(8));
		$this->assertTrue($Tree->find(9));
		$this->assertTrue($Tree->find(10));
	}
	
	public function testRightRotation () {
		$Tree = new AVLTree();
		$Tree->push(10);
		$Tree->push(11);
		$Tree->push(12);
		$this->assertTrue($Tree->getNumNodes() == 3);
		$this->assertTrue($Tree->find(10));
		$this->assertTrue($Tree->find(11));
		$this->assertTrue($Tree->find(12));
	}
	
	public function testLeftRightRotation () {
		$Tree = new AVLTree();
		$Tree->push(10);
		$Tree->push(8);
		$Tree->push(9);
		$this->assertTrue($Tree->getNumNodes() == 3);
		$this->assertTrue($Tree->find(8));
		$this->assertTrue($Tree->find(9));
		$this->assertTrue($Tree->find(10));
	}
	
	public function testRightLeftRotation () {
		$Tree = new AVLTree();
		$Tree->push(10);
		$Tree->push(12);
		$Tree->push(11);
		$this->assertTrue($Tree->getNumNodes() == 3);
		$this->assertTrue($Tree->find(10));
		$this->assertTrue($Tree->find(11));
		$this->assertTrue($Tree->find(12));
	}
	
}

?>
