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

namespace Leap\Core\DB\ORM\Field {

	/**
	 * This class represents a "blob" field in a database table.
	 *
	 * @access public
	 * @class
	 * @package Leap\Core\DB\ORM\Field
	 * @version 2014-01-26
	 */
	class Blob extends \Leap\Core\DB\ORM\Field {

		/**
		 * This constructor initializes the class.
		 *
		 * @access public
		 * @param \Leap\Core\DB\ORM\Model $model                    a reference to the implementing model
		 * @param array $metadata                                   the field's metadata
		 * @throws \Leap\Core\Throwable\Validation\Exception        indicates that the specified value does
		 *                                                          not validate
		 */
		public function __construct(\Leap\Core\DB\ORM\Model $model, Array $metadata = array()) {
			parent::__construct($model, 'Data');

			if (isset($metadata['savable'])) {
				$this->metadata['savable'] = (bool) $metadata['savable'];
			}

			if (isset($metadata['nullable'])) {
				$this->metadata['nullable'] = (bool) $metadata['nullable'];
			}

			if (isset($metadata['filter'])) {
				$this->metadata['filter'] = (string) $metadata['filter'];
			}

			if (isset($metadata['callback'])) {
				$this->metadata['callback'] = (string) $metadata['callback'];
			}

			$this->metadata['control'] = 'textarea';

			if (isset($metadata['label'])) {
				$this->metadata['label'] = (string) $metadata['label'];
			}

			if (isset($metadata['default'])) {
				$default = $metadata['default'];
			}
			else if ( ! $this->metadata['nullable']) {
				$default = new \Leap\Core\Data\ByteString('', \Leap\Core\Data\ByteString::HEXADECIMAL_DATA);
			}
			else {
				$default = NULL;
			}

			if ( ! ($default instanceof \Leap\Core\DB\SQL\Expression)) {
				if ( ! $this->validate($default)) {
					throw new \Leap\Core\Throwable\Validation\Exception('Message: Unable to set default value for field. Reason: Value :value failed to pass validation constraints.', array(':value' => $default));
				}
			}

			$this->metadata['default'] = $default;
			$this->value = $default;
		}

		/**
		 * This method sets the value for the specified key.
		 *
		 * @access public
		 * @override
		 * @param string $key                                       the name of the property
		 * @param mixed $value                                      the value of the property
		 * @throws \Leap\Core\Throwable\Validation\Exception        indicates that the specified value does
		 *                                                          not validate
		 * @throws \Leap\Core\Throwable\InvalidProperty\Exception   indicates that the specified property is
		 *                                                          either inaccessible or undefined
		 */
		public function __set($key, $value) {
			switch ($key) {
				case 'value':
					if ( ! ($value instanceof \Leap\Core\DB\SQL\Expression)) {
						if ($value !== NULL) {
							if (is_string($value)) {
								$value = new \Leap\Core\Data\ByteString($value, \Leap\Core\Data\ByteString::HEXADECIMAL_DATA);
							}
							if ( ! $this->validate($value)) {
								throw new \Leap\Core\Throwable\Validation\Exception('Message: Unable to set the specified property. Reason: Value :value failed to pass validation constraints.', array(':value' => $value));
							}
						}
						else if ( ! $this->metadata['nullable']) {
							$value = $this->metadata['default'];
						}
					}
					if (isset($this->metadata['callback']) AND ! $this->model->{$this->metadata['callback']}($value)) {
						throw new \Leap\Core\Throwable\Validation\Exception('Message: Unable to set the specified property. Reason: Value :value failed to pass validation constraints.', array(':value' => $value));
					}
					$this->metadata['modified'] = TRUE;
					$this->value = $value;
					break;
				case 'modified':
					$this->metadata['modified'] = (bool) $value;
					break;
				default:
					throw new \Leap\Core\Throwable\InvalidProperty\Exception('Message: Unable to set the specified property. Reason: Property :key is either inaccessible or undefined.', array(':key' => $key, ':value' => $value));
					break;
			}
		}

		/**
		 * This method validates the specified value against any constraints.
		 *
		 * @access protected
		 * @override
		 * @param mixed $value                                      the value to be validated
		 * @return boolean                                          whether the specified value validates
		 */
		protected function validate($value) {
			if ($value !== NULL) {
				if ( ! ($value instanceof $this->metadata['type'])) {
					return FALSE;
				}
			}
			return parent::validate($value);
		}

	}

}