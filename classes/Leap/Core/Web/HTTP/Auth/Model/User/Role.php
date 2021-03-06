<?php

/**
 * Copyright © 2011–2015 Spadefoot Team.
 * Copyright © 2012 CubedEye.
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

namespace Leap\Core\Web\HTTP\Auth\Model\User {

	/**
	 * This class represents a record in the "user_roles" table.
	 *
	 * @access public
	 * @class
	 * @package Leap\Core\Model\User
	 * @version 2014-01-25
	 */
	class Role extends \Leap\Core\DB\ORM\Model {

		/**
		 * This constructor instantiates this class.
		 *
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			$this->fields = array(
				'user_id' => new \Leap\Core\DB\ORM\Field\Integer($this, array(
					'max_length' => 10,
					'nullable' => FALSE,
					'unsigned' => TRUE,
				)),
				'role_id' => new \Leap\Core\DB\ORM\Field\Integer($this, array(
					'max_length' => 10,
					'nullable' => FALSE,
					'unsigned' => TRUE,
				)),
			);

			$this->relations = array(
				'user' => new \Leap\Core\DB\ORM\Relation\BelongsTo($this, array(
					'child_key' => array('user_id'),
					'parent_key' => array('id'),
					'parent_model' => '\\Leap\Core\\Web\\HTTP\\Auth\\Model\\User',
				)),
				'role' => new \Leap\Core\DB\ORM\Relation\BelongsTo($this, array(
					'child_key' => array('role_id'),
					'parent_key' => array('id'),
					'parent_model' => '\\Leap\Core\\Web\\HTTP\\Auth\\Model\\Role',
				)),
			);
		}

		/**
		 * This method returns the data source name.
		 *
		 * @access public
		 * @override
		 * @static
		 * @param integer $instance                                 the data source instance to be used (e.g.
		 *                                                          0 = master, 1 = slave, 2 = slave, etc.)
		 * @return string                                           the data source name
		 */
		public static function data_source($instance = 0) {
			return 'default';
		}

		/**
		 * This method returns whether the primary key auto increments.
		 *
		 * @access public
		 * @override
		 * @static
		 * @return boolean                                          whether the primary key auto increments
		 */
		public static function is_auto_incremented() {
			return FALSE;	
		}

		/**
		 * This method returns the primary key for the database table.
		 *
		 * @access public
		 * @override
		 * @static
		 * @return array                                            the primary key
		 */
		public static function primary_key() {
			return array('user_id', 'role_id');	
		}

		/**
		 * This method returns the database table's name.
		 *
		 * @access public
		 * @override
		 * @static
		 * @return string                                           the database table's name
		 */
		public static function table() {
			return 'user_roles';
		}

	}

}