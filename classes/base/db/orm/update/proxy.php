<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Copyright 2011 Spadefoot
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * This class builds an SQL update statement.
 *
 * @package Leap
 * @category ORM
 * @version 2011-11-27
 *
 * @abstract
 */
abstract class Base_DB_ORM_Update_Proxy extends Kohana_Object implements DB_SQL_Statement {

    /**
     * This variable stores a reference to the data source.
     *
     * @access protected
     * @var DB_DataSource
     */
    protected $source = NULL;

    /**
     * This variable stores an instance of the SQL builder class.
     *
     * @access protected
     * @var DB_SQL_Update_Builder
     */
    protected $builder = NULL;

    /**
     * This constructor instantiates this class using the specified model's name.
     *
     * @access public
     * @param string $model                  the model's name
     */
    public function __construct($model) {
        $model = DB_ORM_Model::model_name($model);
        $this->source = new DB_DataSource(call_user_func(array($model, 'data_source')));
        $builder = 'DB_' . $this->source->get_resource_type() . '_Update_Builder';
        $this->builder = new $builder();
        $table = call_user_func(array($model, 'table'));
        $this->builder->table($table);
    }

    /**
     * This function sets the associated value with the specified column.
     *
     * @access public
     * @param string $column                the column to be set
     * @param string $value                 the value to be set
     * @return DB_SQL_Update_Builder        a reference to the current instance
     */
    public function set($column, $value) {
        $this->builder->set($column, $value);
        return $this;
    }

    /**
     * This function either opens or closes a "where" group.
     *
     * @access public
     * @param string $parenthesis           the parenthesis to be used
     * @param string $connector             the connector to be used
     * @return DB_SQL_Update_Builder        a reference to the current instance
     */
    public function where_block($parenthesis, $connector = 'AND') {
        $this->builder->where_block($parenthesis, $connector);
        return $this;
    }

    /**
     * This function adds a "where" constraint.
     *
     * @access public
     * @param string $column                the column to be constrained
     * @param string $operator              the operator to be used
     * @param string $value                 the value the column is constrained with
     * @param string $connector             the connector to be used
     * @return DB_SQL_Update_Builder        a reference to the current instance
     */
	public function where($column, $operator, $value, $connector = 'AND') {
		$this->builder->where($column, $operator, $value, $connector);
		return $this;
	}

    /**
     * This function sorts a column either ascending or descending order.
     *
     * @access public
     * @param string $column                the column to be sorted
     * @param boolean $descending           whether to sort in descending order
     * @return DB_SQL_Update_Builder        a reference to the current instance
     */
	public function order_by($column, $descending = FALSE, $nulls = 'DEFAULT') {
		$this->builder->order_by($column, $descending, $nulls);
		return $this;
	}

    /**
     * This function sets a "limit" constraint on the statement.
     *
     * @access public
     * @param integer $limit                the "limit" constraint
     * @return DB_SQL_Update_Builder        a reference to the current instance
     */
	public function limit($limit) {
		$this->builder->limit($limit);
		return $this;
	}

    /**
     * This function sets an "offset" constraint on the statement.
     *
     * @access public
     * @param integer $offset               the "offset" constraint
     * @return DB_SQL_Update_Builder        a reference to the current instance
     */
    public function offset($offset) {
        $this->builder->offset($offset);
        return $this;
    }

	/**
	 * This function returns the SQL statement.
	 *
	 * @access public
	 * @param boolean $terminated           whether to add a semi-colon to the end
	 *                                      of the statement
	 * @return string                       the SQL statement
	 */
	public function statement($terminated = TRUE) {
	    return $this->builder->statement($terminated);
	}

    /**
     * This function executes the SQL statement.
     *
     * @access public
     */
    public function execute() {
        $connection = DB_Connection_Pool::instance()->get_connection($this->source);
		$connection->execute($this->statement());
    }

}
?>