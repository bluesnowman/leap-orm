<?php

/**
 * Copyright © 2011–2015 Spadefoot Team.
 *
 * Unless otherwise noted, Leap is licensed under the Apache License,
 * Version 2.0 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License
 * at:
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Leap\Core {

	/**
	 * This class represents a typed enumeration.
	 *
	 * @abstract
	 * @access public
	 * @class
	 * @package Leap\Core
	 * @version 2015-03-07
	 */
	abstract class Enum extends \Leap\Core\Object {

		/**
		 * This variable stores the name assigned to the enumeration.
		 *
		 * @access protected
		 * @var string                                              the name of the enumeration
		 */
		protected $__name;

		/**
		 * This variable stores the ordinal value assigned to the enumeration.
		 *
		 * @access protected
		 * @var integer                                             the ordinal value assigned to the enumeration
		 */
		protected $__ordinal;

		/**
		 * This variable stores the value assigned to the enumeration.
		 *
		 * @access protected
		 * @var mixed                                               the value assigned to the enumeration
		 */
		protected $__value;

		/**
		 * This method is purposely disabled to prevent the cloning of the enumeration.
		 *
		 * @access public
		 * @final
		 * @throws \Leap\Core\Throwable\CloneNotSupported\Exception indicates that the object cannot
		 *                                                          be cloned
		 */
		public final function __clone() {
			throw new \Leap\Core\Throwable\CloneNotSupported\Exception('Unable to clone object. Class may not be cloned and should be treated as immutable.');
		}

		/**
		 * This constructor intiializes the enumeration with the specified properties.
		 *
		 * @abstract
		 * @access protected
		 * @param string $name                                      the name of the enumeration
		 * @param mixed $value                                      the value to be assigned to the enumeration
		 */
		protected abstract function __construct($name, $value);

		/**
		 * This destructor ensures that any resources are properly disposed.
		 *
		 * @access public
		 */
		public function __destruct() {
			parent::__destruct();
			unset($this->__name);
			unset($this->__ordinal);
			unset($this->__value);
		}

		/**
		 * This method evaluates whether the specified objects is equal to the current object.
		 *
		 * @access public
		 * @param mixed $object                                     the object to be evaluated
		 * @return boolean                                          whether the specified object is equal
		 *                                                          to the current object
		 */
		public function __equals($object) {
			return (($object !== null) && ($object instanceof \Leap\Core\Enum) && ($object->__ordinal() == $this->__ordinal()));
		}

		/**
		 * This method returns the name assigned to the enumeration.
		 *
		 * @access public
		 * @return string                                           the name assigned to the enumeration
		 */
		public function __name() {
			return $this->__name;
		}

		/**
		 * This method returns a string representing the enumeration.
		 *
		 * @access public
		 * @return string                                           a string representing the enumeration
		 */
		public function __toString() {
			return '' . $this->__value;
		}

		/**
		 * This method returns the ordinal value assigned to the enumeration.
		 *
		 * @access public
		 * @return integer                                          the ordinal value assigned to the enumeration
		 */
		public function __ordinal() {
			return $this->__ordinal;
		}

		/**
		 * This method returns the value assigned to the enumeration.
		 *
		 * @access public
		 * @return mixed                                            the value assigned to the enumeration
		 */
		public function __value() {
			return $this->__value;
		}

	}

}
