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
	 * This class represents a record in the "user_tokens" table.
	 *
	 * @access public
	 * @class
	 * @package Leap\Core\Model\User
	 * @version 2014-01-25
	 */
	class Token extends \Leap\Core\DB\ORM\Model {

		/**
		 * This constructor instantiates this class.
		 *
		 * @access public
		 */
		public function __construct() {
			parent::__construct();

			$this->fields = array(
				'id' => new \Leap\Core\DB\ORM\Field\Integer($this, array(
					'max_length' => 11,
					'nullable' => FALSE,
					'unsigned' => TRUE,
				)),
				'user_id' => new \Leap\Core\DB\ORM\Field\Integer($this, array(
					'max_length' => 11,
					'nullable' => FALSE,
					'unsigned' => TRUE,
				)),
				'user_agent' => new \Leap\Core\DB\ORM\Field\String($this, array(
					'max_length' => 40,
					'nullable' => FALSE,
				)),
				'token' => new \Leap\Core\DB\ORM\Field\String($this, array(
					'max_length' => 40,
					'nullable' => FALSE,
				)),
				'type' => new \Leap\Core\DB\ORM\Field\String($this, array(
					'max_length' => 100,
					'nullable' => FALSE,
				)),
				'created' => new \Leap\Core\DB\ORM\Field\Integer($this, array(
					'max_length' => 11,
					'nullable' => FALSE,
					'unsigned' => TRUE,
				)),
				'expires' => new \Leap\Core\DB\ORM\Field\Integer($this, array(
					'max_length' => 11,
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
			);
		}

		/**
		 * This method returns a new token.
		 *
		 * @access public
		 * @return string                                           a new token
		 */
		public function create_token() {
			do {
				$token = sha1(uniqid(\Text::random('alnum', 32), TRUE));
			}
			while(\Leap\Core\DB\SQL::select($this->data_source(\Leap\Core\DB\DataSource::SLAVE_INSTANCE))->from($this->table())->where('token', \Leap\Core\DB\SQL\Operator::_EQUAL_TO_, $token)->query()->is_loaded());
			return $token;
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
		 * This method returns the primary key for the database table.
		 *
		 * @access public
		 * @override
		 * @static
		 * @return array                                            the primary key
		 */
		public static function primary_key() {
			return array('id');	
		}

		/**
		 * This method saves the record matching using the primary key.
		 *
		 * @access public
		 * @override
		 * @param boolean $reload                                   whether the model should be reloaded
		 *                                                          after the save is done
		 * @param boolean $mode                                     TRUE=save, FALSE=update, NULL=automatic
		 */
		public function save($reload = FALSE, $mode = NULL) {
			$this->token = $this->create_token();
			parent::save($reload, $mode);
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
			return 'user_tokens';
		}

	}

}