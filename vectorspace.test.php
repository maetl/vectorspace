<?php
require 'simpletest/autorun.php';
require 'vectorspace.php';

class VectorSpaceTest extends UnitTestCase {

	function testDataStructure() {
		$space = new VectorSpace(array(1,2,3,4));
		$this->assertTrue(4, $space->dimension());
		$this->assertEqual(0, $space->size());
		$space->add("a", new Vector(4));
		$b = new Vector(4);
		$b->add(0,1);
		$b->add(1,2);
		$b->add(2,3);
		$space->add("b", $b);
		$this->assertEqual(2, $space->size());
	}
	
}
	
class VectorTest extends UnitTestCase {

	function testDataStructure() {
		$v = new Vector(2);
		$this->assertTrue($v->isIdentity());
		$v->add(0, 1);
		$this->assertFalse($v->isIdentity());
		$v->add(3, 1);
		$this->assertError();
		$this->assertEqual(1, $v->get(0));
		$this->assertEqual(0, $v->get(1));
	}
	
	function testCommutativityHolds() {
		$v = new Vector(3);
		$v->add(1,2);
		$w = new Vector(3);
		$w->add(1,5);
		$this->assertEqual($v->sum($w), $w->sum($v));
		$result = $v->sum($w);
		$this->assertEqual(7, $result->get(1));
	}
	
	function testAdditiveIdentityHolds() {
		$w = new Vector(2);
		$this->assertTrue($w->equals(new Vector(2)));
		$w->add(0,2);
		$w->add(1,1);
		$this->assertEqual($w, $w->sum(new Vector(2)));
	}
	
	function testSubtraction() {
		$v = new Vector(3);
		$v->add(1,5);
		$w = new Vector(3);
		$w->add(1,2);
		$result = $v->difference($w);
		$this->assertEqual(3, $result->get(1));
	}
	
	function testNegation() {
		$this->assertEqual(-2, Vector::negate(2));
	}
	
	function testUnitVectorConversion() {
		$v = new Vector(2);
		$v->add(0,4);
		$v->add(1,9);
		$u = $v->toUnit();
		$this->assertEqual(1, $u->norm());
		$w = new Vector(2);
		$this->assertEqual(0, $w->norm());
		$u = $v->toUnit();
		$this->assertEqual(1, $u->norm());
	}

}

?>