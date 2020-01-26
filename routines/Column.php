<?php
class Column {

	public $query_string = "";
	private $curr_string = "";
	private $curr_action = "";

	public function __construct($action) {
		$this->curr_action = strtoupper($action);
	}

	private function add_column($thisObj, $name, $size="") {
		$update_clause = ($this->curr_action == "UPDATE") ? " ADD" : "";
		$this->curr_string = $thisObj->add($name, $size);
		$this->query_string .= ($this->query_string == "") ? "{$update_clause}	{$this->curr_string}" : ",{$update_clause} {$this->curr_string}";
	}

	public function string($name, $size="") {
		$this->add_column(new AddString(), $name, $size);
		return $this;
	}

	public function int($name, $size="") {
		$this->add_column(new AddInt(), $name, $size);
		return $this;
	}

	public function float($name, $size="") {
		$this->add_column(new AddFloat(), $name, $size);
		return $this;
	}

	public function double($name, $size="") {
		$this->add_column(new AddDouble(), $name, $size);
		return $this;
	}

	public function char($name, $size="") {
		$this->add_column(new AddChar(), $name, $size);
		return $this;
	}

	public function date($name, $size="") {
		$this->add_column(new AddDate(), $name, $size);
		return $this;
	}

	public function datetime($name, $size="") {
		$this->add_column(new AddDateTime(), $name, $size);
		return $this;
	}
	
	public function null() {
		$this->query_string .= " NULL";
		return $this;
	}

	public function default($default) {
		$this->query_string .=  " DEFAULT '{$default}'";
		return $this;
	}

}

//set, enum
//tinytext, text(size), mediumtext
//timestamp, time, year
//zerofill
//tinyblob, blob(size), mediumblob
//bit(size), tinyint(size), smallint(size), mediumint(size), int(size), bigint(size), DECIMAL
//binary(size), varbinary(size)