<?php
class Column {
	public $query_string = "";
	private $curr_string = "";
	
	public function __construct() {}
	
	private function add_column($name, $type, $size) {
		$this->curr_string = "`{$name}` {$type}";
		if ($size != "") $this->curr_string .= "({$size})"; 
	}
	
	public function make_null() {
		$this->curr_string .= " NULL";
		return $this;
	}
	
	public function set_default($default) {
		$this->curr_string .= " DEFAULT '{$default}'";
		return $this;
	}
	
	public function string($name, $size="") {
		$this->add_column($name, 'VARCHAR', $size);
		$this->collate_query();
		return $this;
	}
	
	public function int($name, $size="") {
		$this->add_column($name, 'INT', $size);
		$this->collate_query();
		return $this;
	}
	
	public function float($name, $size="") {
		$this->add_column($name, 'FLOAT', $size);
		$this->collate_query();
		return $this;
	}
	
	public function double($name, $size="") {
		$this->add_column($name, 'FLOAT', $size);
		$this->collate_query();
		return $this;
	}
	
	public function char($name, $size="") {
		$this->add_column($name, 'CHAR', $size);
		$this->collate_query();
		return $this;
	}
	
	private function collate_query() {
		$this->query_string .= ($this->query_string == "") ? "	{$this->curr_string}" : ", 	{$this->curr_string}";
	}
}
