<?php

class Validate {
	private $_passed = false;
	private $_errors = array();
	private $_db = null;
	private $_countFieldRequired = 0;
	private $_fieldNameRequired = '';

	public function __construct() {
		$this->_db = DB::getInstance();
	}

	private function fieldRequired( $name ) {
		$this->_countFieldRequired ++;
		if ( $this->_countFieldRequired == 1 ) {
			$this->_fieldNameRequired = ucfirst( $name );
		} else {
			$this->_fieldNameRequired .= ", " . $name;
		}
	}

	public function check( $source, $items = array() ) {
		// $item is the name and $rules has the value, which is an array from $items array
		foreach ( $items as $item => $rules ) {
			// $rule is the name and $rule_value has the value of that $rules array
			foreach ( $rules as $rule => $rule_value ) {
				// $value stores the value for the specific field in the form
				$value = trim( $source[ $item ] );
				if ( $rule === 'required' && empty( $value ) ) {
					$this->fieldRequired( $items[ $item ]['name'] );
				} else if ( ! empty( $value ) ) {
					switch ( $rule ) {
						case 'min':
							if ( strlen( $value ) < $rule_value ) {
								$this->setError( "The \"{$items[$item]['name']}\" field must have at least {$rule_value} characters!" );
							}
							break;
						case 'max':
							if ( strlen( $value ) > $rule_value ) {
								$this->setError( ucfirst( $items[ $item ]['name'] ) . " field excess maximum characters of {$rule_value}!" );
							}
							break;
						case 'matches':

							if ( $value != $source[ $rule_value ] ) {
								$this->setError( "\"" . ucfirst( $items[ $item ]['matches'] ) . "\" field does not match with \"{$items[$item]['name']}\"!" );
							}
							break;
						case 'unique':
							$check = $this->_db->get( $rule_value, array( $item, '=', $value ) );
							if ( $check->count() ) {
								$this->setError( "This \"{$items[$item]['name']}\" already exists!" );
							}
							break;
						case 'numeric':
							if ( is_numeric( $value ) ) {
								$this->setError( ucfirst( $items[ $item ]['name'] ) . " field must contain at least one letter!" );
							}
							break;
						case 'numericOnly':
							if ( ! is_numeric( $value ) ) {
								$this->setError( ucfirst( $items[ $item ]['name'] ) . " field must contain only numbers!" );
							}
							break;
					}
				}
			}
		}
		if ( $this->_countFieldRequired > 0 ) {
			if ( $this->_countFieldRequired == 1 ) {
				$this->setError( $this->_fieldNameRequired . " field is required! " );
			} else {
				$this->setError( $this->_fieldNameRequired . " fields are required! " );
			}
		}
		if ( count( $this->getError() ) == 0 ) {
			$this->_passed = true;
		}

		return $this;
	}

	private function setError( $errors ) {
		$this->_errors[] = $errors;
	}

	public function getError() {
		return $this->_errors;
	}

	public function passed() {
		return $this->_passed;
	}
} 