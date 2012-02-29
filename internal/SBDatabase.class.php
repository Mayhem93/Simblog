<?php
/**
* The class that facilitates the use of the idiorm db class
*
* @author		Răzvan Botea<utherr.ghujax@gmail.com>
* @license 		http://www.gnu.org/licenses/gpl.txt
* @copyright	2011-2012 Răzvan Botea
*
* 	PHP 5
*
*	This file is part of Simblog.
*
*   Simblog is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Simblog is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Simblog.  If not, see <http://www.gnu.org/licenses/>.
*/

include_once BLOG_ROOT."/libs/idiorm.php";

class SBDatabase extends ORM {
	
	public function __construct($host, $database, $username, $password, $port=3306) {
		parent::configure("mysql:host={$host}:{$port};dbname={$database}");
		parent::configure("username", $username);
		parent::configure("password", $password);
		parent::configure("dirver_options", array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf-8"));
		parent::configure("logging", true);
	}
	
	/**
	 * Inserts row to DB.
	 * @param string $table Name of the table.
	 * @param array $values column=>value pairs to insert
	 * @return boolean True if successful.
	 */
	public function insertRow($table, array $values) {
		$table = $this->_escapeSQL($table);
		$values = $this->_escapeSQL($values);
		
		$query = parent::for_table($table)->create($values);
		
		return $query->save();
	}
	
	/**
	 * Performs a select operation and counts the rows
	 * @param string $table Name of the table.
	 * @param string $where column=>key pairs for the where statement.
	 * @param string $group_by Name of the column to group by.
	 * @param int $offset
	 * @param int $limit
	 * @return int Number of rows.
	 */
	public function countRows($table, $where = null, $group_by = null,
								$offset = null, $limit = null) {
		
		$table = $this->_escapeSQL($table);
		$where = $this->_escapeSQL($where);
		$group_by = $this->_escapeSQL($group_by);
		$result = parent::for_table($table);
		
		if (func_num_args() == 1)
			return $result->count();

		else {
			
			foreach($where as $column => $value)
				$result = $result->where($column, $value);
			
			if ($group_by)
				$result = $result->group_by($group_by);
			
			if ($limit)
				$result = $result->limit($limit);
			
			if ($offset)
				$result = $result->offset($offset);
			
			return $result->count();
		}
	}
	
	/**
	 * Deletes a row.
	 * @param string $table Name of the table.
	 * @param array $where column=>key values for the where statement.
	 * @return boolean true if sucessful false otherwise.
	 */
	public function deleteRows($table, array $where) {
		$table = $this->_escapeSQL($table);
		$where = $this->_escapeSQL($where);
		
		$query = parent::for_table($table);
		foreach($where as $col_name => $value)
			$query = $query->where($col_name, $value);

		$array = $query->find_many();
		
		foreach($array as $q)
			$q->delete();
		
		return count($array) ? true : false;
	}
	
	/**
	 * Updates a row.
	 * @param string $table Name of the table.
	 * @param array $where column=>key pairs for the where statement.
	 * @param array $update_set column=>key pairs for the SET statement
	 * @return boolean True if successful.
	 */
	public function updateRows($table, array $where, array $update_set) {
		$table = $this->_escapeSQL($table);
		$where = $this->_escapeSQL($where);
		$update_set = $this->_escapeSQL($update_set);
		
		$query = parent::for_table($table);
		
		foreach($where as $col_name => $value)
			$query = $query->where($col_name, $value);
		
		$query = $query->find_one();
		
		foreach($update_set as $col_name => $value)
			$query->set($col_name, $value);
		
		$query->save();
		return parent::get_last_query();
	}
	
	/**
	 * Executes query.
	 * @param string $query
	 * @return Associative array with the results.
	 */
	public function query($query) {
		//$query = $this->_escapeSQL($query);
		
		$results = parent::for_table("")->raw_query($query, array())->find_many();
		
		return $this->_toArray($results);
	}
	
	/**
	 * Executes query and returns 1 row.
	 * @param string $query
	 * @return Associative array with the results.
	 */
	public function querySingleRow($query) {
		$query = $this->_escapeSQL($query);
		
		$results = parent::for_table("")->raw_query($query, array())->find_one();
		
		return $this->_toArray($results);
	}
	
	/**
	 * Gets the first value from the first column of a query.
	 * @param string $query The query.
	 * @return string
	 */
	public function querySingleValue($query) {
		$result = $this->querySingleRow($query);
		
		return $result[0];
	}
	
	/**
	 * Returns data from DB.
	 * @param string $table Name of the table.
	 * @param string $columns Name of the columns to be included. Defaults to '*'
	 * @param array $where column=>key pairs for the where statement.
	 * @param string $group_by Name of the column to group by.
	 * @param string $sort Valid strings: "ASC" or "DESC"
	 * @param string $sort_column The column to sort by.
	 * @param int $offset
	 * @param int $limit
	 * @return Associative array with the results.
	 */
	public function selectRows($table, $columns = "*", $where = null, 
								$group_by = null, $sort = null, $sort_column = null, 
								$offset = null, $limit = null) {
		
		$table = $this->_escapeSQL($table);
		$where = $this->_escapeSQL($where);
		$group_by = $this->_escapeSQL($group_by);
		$sort_column = $this->_escapeSQL($sort_column);
		
		if ($sort)
			if (!($sort == "ASC" || $sort == "DESC"))
				$sort = null;
		if (is_int($offset) && is_int($limit)) {
			$offset = null;
			$limit = null;
		}
		
		if (func_num_args() == 1) {
			$result = parent::for_table($table)->find_many();
			
			return $this->_toArray($results);
		}
		else {
			$query = parent::for_table($table);
			if(is_array($columns))
				foreach($columns as $c)
					$query = $query->select($c);
			else
				$query = $query->select_expr($columns);
				
			if (func_num_args() == 2)
				return $query->find_many()->as_array();
			
			if ($where)
				foreach($where as $key => $value)
					$query = $query->where($key, $value);
			
			if ($group_by)
				$query = $query->group_by($group_by);
			
			if ($sort) {
				if($sort == "ASC")
					$query = $query->order_by_asc($sort_column);
				else
					$query = $query->order_by_desc($sort_column);
			}
			
			if ($limit)
				$query = $query->limit($limit);
			if ($offset)
				$query = $query->offset($offset);
			
			$result = $query->find_many();
			
			return $this->_toArray($result);
		}
		
	}
	
	/**
	 * Prepares the query for use.
	 * @param mixed $data The query, column name or an array of column names.
	 * @return mixed The escaped variables. Array or string.
	 */
	private function _escapeSQL($data) {
		if (is_string($data))
			return mysql_escape_string($data);
		else if (is_array($data)) {
			$result = array();
			foreach($data as $key => $value)
				$result[mysql_escape_string($key)] = mysql_escape_string($value);
			
			return $result;
		}
		
		return $data;
	}
	
	/**
	 * Transforms an array of ORM objects to an assoc. array
	 * @param array $objects Array of ORM objects
	 * @return Associatve array
	 */
	private function _toArray(array $objects) {
		$result = array();
		
		if(!empty($objects))
			foreach($objects as $obj)
				$result[] = $obj->as_array();
		
		return $result;
	}
	
}