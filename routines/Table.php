<?php
class Table {
	static public $create_query = "";
	static public $update_query = "";
	static public $index_query  = "";
	static private $databaseObj = null;
	static private $curr_db = "";
	
	public function __construct() {}
	
	public function initAction() {
		self::$curr_db = $_SESSION['curr_db'];
		
		self::$curr_db = self::$curr_db."2";
		self::$databaseObj = new Db(self::$curr_db);
	}
	
    public static function create($table_name, $action_function, $dbEngine="") {
		$colObj 	= new Column("create");
		$engineQry 	= ($dbEngine != "") ? "ENGINE={$dbEngine}" : "";
		$action_function($colObj);
		
		$thisQuery  =   "";
		$thisQuery .= 	"CREATE TABLE IF NOT EXISTS `{$table_name}` (\n";
		$thisQuery .= 	"	{$colObj->query_string}";
		$thisQuery .= 	"){$engineQry};\n\n";
		(new self)->initAction();
		self::$create_query = $thisQuery;
		return self::$databaseObj->execute($thisQuery);
	}


	public static function update($table_name, $action_function) {
		$colObj = new Column("update");
		$action_function($colObj);
		$thisQuery = "ALTER TABLE `{$table_name}` {$colObj->query_string}";
		(new self)->initAction();
		self::$update_query = $thisQuery;
		return self::$databaseObj->execute($thisQuery);
	}
	
	
	public static function index($table_name, $action_function) {
		$colObj = new Column($table_name);
		$action_function($colObj);
		(new self)->initAction();
		self::$index_query = $colObj->query_string;
		return self::$databaseObj->execute($colObj->query_string);
	}
	
	
	public static function changesize($table_name, $action_function) {
		$colObj = new Column("change_size");
		$action_function($colObj);
		(new self)->initAction();
		$replace_str = preg_replace('/(`\w+`)/', "ALTER TABLE {$table_name} MODIFY COLUMN $1", $colObj->query_string);
		return self::$databaseObj->execute($replace_str);
	}
}