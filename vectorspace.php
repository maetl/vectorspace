<?php
/**
 * A collection of vectors as points in a field.
 */
class VectorSpace {
	var $field;
	var $members;
	var $count;
	
	function VectorSpace($field) {
		$this->field = $field;
		$this->members = array();
		$this->count = 0;
	}
	
	function dimension() {
		return count($this->field);
	}
	
	function size() {
		return $this->count;
	}
	
	function add($label, $vector) {
		if ($vector->dimension() != $this->dimension()) {
			trigger_error("Vector not of equivalent dimension for VectorSpace");
			return;
		}
		if ($this->inField($vector->getPoints())) {
			$this->members[$label] = $vector;
			$this->count++;
		} else {
			trigger_error("Vector contains points that do not map to Field");
		}
	}
	
	function inField($points) {
		foreach($points as $point) {
			if (!in_array($point, array_keys($this->field))) return false;
		}
		return true;
	}

}

/**
 * Vector of arbitrary length
 */
class Vector {
	var $dimension;
	var $points;

	function Vector($arg) {
		if (is_array($arg)) {
			$this->points = $arg;
			$this->dimension = count($arg);
		} else {
			$this->dimension = (int)$arg;
			$this->initializeIdentity();
		}
	}
	
	/**
	 * @private
	 */
	function initializeIdentity() {
		$index = 0;
		$this->points = array();
		while($index < $this->dimension) {
			$this->points[$index] = 0;
			$index++;
		}
	}
	
	/**
	 * Dimension (size) of this Vector
	 */
	function dimension() {
		return $this->dimension;
	}
	
	/**
	 * Is this an identity element (zero vector)?
	 */
	function isIdentity() {
		return (array_sum($this->points) == 0);
	}
	
	/**
	 * Equality comparison of this and supplied vector
	 */
	 function equals($vector) {
		 return ($this->getPoints() == $vector->getPoints());
	 }
	 
	 /**
	  * Returns Vector result from addition of supplied Vector
	  */
	  function sum($vector) {
		  if ($vector->isIdentity()) {
			  return $this;
		  } elseif ($this->isIdentity()) {
			  return $vector;
		  } else {
			  $result = new Vector($this->dimension);
			  for($i=0; $i<$this->dimension; $i++) {
				  $result->add($i, $this->get($i) + $vector->get($i));
			  }
			  return $result;
		  }
	  }
	  
	/**
	 * Returns Vector result from subtraction of supplied Vector
	 */
	function difference($vector) {
		$vector->replacePoints(array_map(array($this,'negate'), $vector->getPoints()));
		return $this->sum($vector);
	}
	
	/**
	 * Negation operation on an integer
	 */
	function negate($int) {
		return -$int;
	}
	
	/**
	 * Calculate the norm (length) of this Vector
	 * |V| = sqrt( v1^2 + v2^2 + vn^2 ...)
	 *
	 * @return float
	 */
	function norm() {
		$sum = 0;
		for ($i=0;$i<$this->dimension; $i++) {
			$sum += pow($this->get($i), 2);
		}
		return sqrt($sum);
	}
	
	/**
	 * Return a normalized unit vector from this vector 
	 * ^V = V / |V|
	 *
	 * @return Vector
	 */
	 function toUnit() {
		 $norm = $this->norm();
		 $result = new Vector($this->dimension);
		 for ($i=0;$i<$this->dimension; $i++) {
			 $result->add($i, $this->get($i)/$norm);
		 }
		 return $result;
	 }
	
	/**
	 * Calculate the scalar result of a dot product operation
	 */
	 function dotProduct($vector) {
		 
	 }
	
	/**
	 * Add a value to the vector at specified index
	 */
	function add($index, $value) {
		if ($index < $this->dimension) {
			$this->points[$index] = $value;
		} else {
			trigger_error("Index [$index] out of range for dimension [{$this->dimension}]");
		}
	}
	
	/**
	 * Get the value of the point at the specified index
	 */
	function get($index) {
		return $this->points[$index];
	}
	
	/**
	 * Get the values of each point as an ordered list
	 */
	 function getPoints() {
		 return $this->points;
	 }
	 
	 /**
	  * Re-populate this Vector with an array of points
	  */
	 function replacePoints($points) {
		 $this->points = $points;
	 }
	
}

?>