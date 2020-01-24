<?php
class Table {
	public function __construct() {}
	
    static function create($table_name, $action_function) {
		$colObj = new Column();
		$action_function($colObj);
		$thisQuery = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
			{$colObj->query_string}
		);";
	}
}