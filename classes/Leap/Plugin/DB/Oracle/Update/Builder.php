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

namespace Leap\Plugin\DB\Oracle\Update {

	/**
	 * This class builds an Oracle update statement.
	 *
	 * @access public
	 * @class
	 * @package Leap\Plugin\DB\Oracle\Update
	 * @version 2014-07-04
	 *
	 * @see http://download.oracle.com/docs/cd/B19306_01/server.102/b14200/statements_10007.htm#i2067715
	 * @see http://psoug.org/reference/update.html
	 */
	class Builder extends \Leap\Core\DB\SQL\Update\Builder {

		/**
		 * This method returns the SQL command.
		 *
		 * @access public
		 * @override
		 * @param boolean $terminated                               whether to add a semi-colon to the end
		 *                                                          of the statement
		 * @return \Leap\Core\DB\SQL\Command                        the SQL command
		 *
		 * @see http://www.oracle.com/technetwork/issue-archive/2006/06-sep/o56asktom-086197.html
		 * @see http://geekswithblogs.net/WillSmith/archive/2008/06/18/oracle-update-with-join-again.aspx
		 */
		public function command($terminated = TRUE) {
			if ( ! empty($this->data['order_by']) OR ($this->data['limit'] > 0) OR ($this->data['offset'] > 0)) {
				$text = "SELECT * FROM {$this->data['table']}";

				if ( ! empty($this->data['where'])) {
					$append = FALSE;
					$text .= ' WHERE ';
					foreach ($this->data['where'] as $where) {
						if ($append AND ($where[1] != \Leap\Core\DB\SQL\Builder::_CLOSING_PARENTHESIS_)) {
							$text .= " {$where[0]} ";
						}
						$text .= $where[1];
						$append = ($where[1] != \Leap\Core\DB\SQL\Builder::_OPENING_PARENTHESIS_);
					}
				}

				if ( ! empty($this->data['order_by'])) {
					$text .= ' ORDER BY ' . implode(', ', $this->data['order_by']);
				}

				if (($this->data['limit'] > 0) AND ($this->data['offset'] > 0)) {
					$max_row_to_fetch = $this->data['offset'] + ($this->data['limit'] - 1);
					$min_row_to_fetch = $this->data['offset'];
					$text = "SELECT * FROM (SELECT \"t0\".*, ROWNUM AS \"rn\" FROM ({$text}) \"t0\" WHERE ROWNUM <= {$max_row_to_fetch}) WHERE \"rn\" >= {$min_row_to_fetch}";
				}
				else if ($this->data['limit'] > 0) {
					$text = "SELECT * FROM ({$text}) WHERE ROWNUM <= {$this->data['limit']}";
				}
				else if ($this->data['offset'] > 0) {
					$text = "SELECT * FROM ({$text}) WHERE ROWNUM >= {$this->data['offset']}";
				}

				$text = "UPDATE ({$text})";

				if ( ! empty($this->data['column'])) {
					$text .= ' SET ' . implode(', ', array_values($this->data['column']));
				}
			}
			else {
				$text = "UPDATE {$this->data['table']}";

				if ( ! empty($this->data['column'])) {
					$text .= ' SET ' . implode(', ', array_values($this->data['column']));
				}

				if ( ! empty($this->data['where'])) {
					$append = FALSE;
					$text .= ' WHERE ';
					foreach ($this->data['where'] as $where) {
						if ($append AND ($where[1] != \Leap\Core\DB\SQL\Builder::_CLOSING_PARENTHESIS_)) {
							$text .= " {$where[0]} ";
						}
						$text .= $where[1];
						$append = ($where[1] != \Leap\Core\DB\SQL\Builder::_OPENING_PARENTHESIS_);
					}
				}
			}

			if ($terminated) {
				$text .= ';';
			}

			$command = new \Leap\Core\DB\SQL\Command($text);
			return $command;
		}

	}

}