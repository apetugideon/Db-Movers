<?php
class Table {
	public function __construct() {}
	
    static function create($table_name, $action_function) {
		$colObj = new Column("create");
		$action_function($colObj);
		$thisQuery 	= 	"CREATE TABLE IF NOT EXISTS `{$table_name}` (
							{$colObj->query_string}
						);";
		//echo "thisQuery === $thisQuery\n\n";
	}


	static function update($table_name, $action_function) {
		$colObj = new Column("update");
		$action_function($colObj);
		$thisQuery = "ALTER TABLE `{$table_name}` {$colObj->query_string}";
		//echo "thisQuery === $thisQuery\n\n";
	}
}