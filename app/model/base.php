<?php

namespace Model;

abstract class Base extends \DB\SQL\Mapper {

	protected $fields = array();

	function __construct($table_name = null) {
		$f3 = \Base::instance();

		if(empty($this->_table_name)) {
			if(empty($table_name)) {
				$f3->error(500, "Model instance does not have a table name specified.");
			} else {
				$this->table_name = $table_name;
			}
		}

		parent::__construct($f3->get("db.instance"), $this->_table_name, null, $f3->get("cache_expire.db"));
		return $this;
	}

	// Set object created datetime if possible
	function save() {
		if(array_key_exists("created", $this->fields) && !$this->query) {
			$this->set("created", now());
		}
		return parent::save();
	}

	// Safely delete object if possible, if not, erase the record.
	function delete() {
		if(array_key_exists("deleted", $this->fields)) {
			$this->set("deleted", now());
			return $this->save();
		} else {
			return $this->erase();
		}
	}

	// Load by ID directly, ignoring records with a deleted datetime
	function load($filter=NULL, array $options=NULL, $ttl=0) {
		if(is_numeric($filter)) {
			if(array_key_exists("deleted", $this->fields)) {
				return parent::load(array("id = ? AND deleted IS NULL", $filter), $options, $ttl);
			} else {
				return parent::load(array("id = ?", $filter), $options, $ttl);
			}
		} else {
			return parent::load($filter, $options, $ttl);
		}
	}

	// Get most recent value of field
	protected function get_prev($key) {
		if(!$this->query) {
			return null;
		}
		$prev_fields = $this->query[count($this->query) - 1]->fields;
		return array_key_exists($key, $prev_fields) ? $prev_fields[$key]["value"] : null;
	}

}
