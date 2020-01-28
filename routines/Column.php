<?php
class Column {

	public $query_string = "";
	private $curr_string = "";
	private $curr_action = "";


	public function __construct($action="") {
		$this->curr_action = ($action == "create" || $action == "update") ? strtoupper($action) : $action;
	}


	private function add_column($thisObj, $name, $size="") {
		if ($this->curr_action == "change_size") {
			$this->curr_string   = $thisObj->add($name, $size);
			$this->query_string .= "{$this->curr_string};\n";
		} else {
			$update_clause = ($this->curr_action == "UPDATE") ? " ADD" : "";
			$this->curr_string = $thisObj->add($name, $size);
			$this->query_string .= ($this->query_string == "") ? "{$update_clause}	{$this->curr_string}" : ",{$update_clause} {$this->curr_string}";
		}
	}



	//INTEGER VARIABLES
	public function tinyint($name, $size="") {
		$this->add_column(new AddTinyInt(), $name, $size);
		return $this;
	}
	
	
	public function smallint($name, $size="") {
		$this->add_column(new AddSmallInt(), $name, $size);
		return $this;
	}
	
	
	public function int($name, $size="") {
		$this->add_column(new AddInt(), $name, $size);
		return $this;
	}
	
	public function mediumint($name, $size="") {
		$this->add_column(new AddMediumInt(), $name, $size);
		return $this;
	}
	
	
	public function bigint($name, $size="") {
		$this->add_column(new AddBigInt(), $name, $size);
		return $this;
	}
	


	//DECIMAL VARIABLES
	public function float($name, $size="") {
		$this->add_column(new AddFloat(), $name, $size);
		return $this;
	}

	public function double($name, $size="") {
		$this->add_column(new AddDouble(), $name, $size);
		return $this;
	}


	
	//DATE-TIME VARIABLES
	public function date($name, $size="") {
		$this->add_column(new AddDate(), $name, $size);
		return $this;
	}


	public function datetime($name, $size="") {
		$this->add_column(new AddDateTime(), $name, $size);
		return $this;
	}
	
	
	public function timestamp($name, $size="") {
		$this->add_column(new AddTimeStamp(), $name, $size);
		return $this;
	}
	
	
	public function time($name, $size="") {
		$this->add_column(new AddTime(), $name, $size);
		return $this;
	}
	
	
	public function year($name, $size="") {
		$this->add_column(new AddYear(), $name, $size);
		return $this;
	}
	
	
	
	//STRING/CHARACTER VARIABLES
	public function char($name, $size="") {
		$this->add_column(new AddChar(), $name, $size);
		return $this;
	}
	
	
	public function string($name, $size="") {
		$this->add_column(new AddString(), $name, $size);
		return $this;
	}
	
	
	
	//TEXT VARIABLES
	public function tinytext($name, $size="") {
		$this->add_column(new AddTinyText(), $name, $size);
		return $this;
	}
	
	
	public function text($name, $size="") {
		$this->add_column(new AddText(), $name, $size);
		return $this;
	}
	
	
	public function mediumtext($name, $size="") {
		$this->add_column(new AddMediumText(), $name, $size);
		return $this;
	}
	
	
	public function longtext($name, $size="") {
		$this->add_column(new AddLongText(), $name, $size);
		return $this;
	}
	
	
	
	//BLOB VARIABLES
	public function tinyblob($name, $size="") {
		$this->add_column(new AddTinyBlob(), $name, $size);
		return $this;
	}
	
	
	public function blob($name, $size="") {
		$this->add_column(new AddBlob(), $name, $size);
		return $this;
	}
	
	
	public function mediumblob($name, $size="") {
		$this->add_column(new AddMediumBlob(), $name, $size);
		return $this;
	}
	
	
	public function longblob($name, $size="") {
		$this->add_column(new AddLongBlob(), $name, $size);
		return $this;
	}
	
	
	
	//OTHERS
	public function primary() {
		$this->query_string .= " PRIMARY KEY";
		return $this;
	}
	
	
	public function unique($col_arr) {
		$impl = implode(",", $col_arr);
		$this->query_string .= ", PRIMARY KEY({$impl})";
		return $this;
	}
	
	
	public function increment() {
		$this->query_string .= " AUTO_INCREMENT";
		return $this;
	}
	
	
	public function null() {
		$this->query_string .= " NULL";
		return $this;
	}


	public function default($default) {
		if ($default == 'CURRENT_TIMESTAMP') {
			$this->query_string .=  " DEFAULT {$default}";
		} else {
			$this->query_string .=  " DEFAULT '{$default}'";
		}
		return $this;
	}
	
	
	public function zerofill() {
		$this->query_string .= " ZEROFILE";
		return $this;
	}
	
	
	public function index($name, $index_arr) {
		$impl = implode(",", $index_arr);
		$this->query_string .= "CREATE INDEX {$name} ON {$this->curr_action} ({$impl});<br>";
		return $this;
	}
	
	
	public function unique_index($name, $index_arr) {
		$impl = implode(",", $index_arr);
		$this->query_string .= "CREATE UNIQUE INDEX {$name} ON {$this->curr_action} ({$impl});<br>";
		return $this;
	}
}