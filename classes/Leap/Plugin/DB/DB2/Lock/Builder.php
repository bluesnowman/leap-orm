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

namespace Leap\Plugin\DB\DB2\Lock {

	/**
	 * This class builds a DB2 lock statement.
	 *
	 * @access public
	 * @class
	 * @package Leap\Plugin\DB\DB2\Lock
	 * @version 2014-07-04
	 *
	 * @see http://publib.boulder.ibm.com/infocenter/dzichelp/v2r2/topic/com.ibm.db2.doc.sqlref/rlktb.htm
	 * @see http://publib.boulder.ibm.com/infocenter/db2luw/v8/topic/com.ibm.db2.udb.doc/admin/r0000972.htm
	 */
	class Builder extends \Leap\Core\DB\SQL\Lock\Builder {

		/**
		 * This method acquires the required locks.
		 *
		 * @access public
		 * @override
		 * @return \Leap\Core\DB\SQL\Lock\Builder                   a reference to the current instance
		 */
		public function acquire() {
			$this->connection->begin_transaction();
			foreach ($this->data as $command) {
				$this->connection->execute($command);
			}
			return $this;
		}

		/**
		 * This method adds a lock definition.
		 *
		 * @access public
		 * @override
		 * @param string $table                                     the table to be locked
		 * @param array $hints                                      the hints to be applied
		 * @return \Leap\Core\DB\SQL\Lock\Builder                   a reference to the current instance
		 */
		public function add($table, Array $hints = NULL) {
			$table = $this->precompiler->prepare_identifier($table);
			$text = "LOCK TABLE {$table} IN ";
			$mode = 'EXCLUSIVE';
			if ($hints !== NULL) {
				foreach ($hints as $hint) {
					if (preg_match('/^(EXCLUSIVE|SHARE)$/i', $hint)) {
						$mode = strtoupper($hint);
					}
				}
			}
			$text .= $mode . ' MODE;';
			$this->data[$table] = new \Leap\Core\DB\SQL\Command($text);
			return $this;
		}

		/**
		 * This method releases all acquired locks.
		 *
		 * @access public
		 * @override
		 * @param string $method                                    the method to be used to release
		 *                                                          the lock(s)
		 * @return \Leap\Core\DB\SQL\Lock\Builder                   a reference to the current instance
		 */
		public function release($method = '') {
			switch (strtoupper($method)) {
				case 'ROLLBACK':
					$this->connection->rollback();
				break;
				case 'COMMIT':
				default:
					$this->connection->commit();
				break;
			}
			return $this;
		}

	}

}