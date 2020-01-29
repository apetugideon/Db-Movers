<?php
class Table {
	static public  $curr_query 	= "";
	static private $databaseObj = null;
	static private $curr_db 	= "";
	
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
		$thisQuery .=   "SET sql_mode = '';";
		$thisQuery .= 	"CREATE TABLE IF NOT EXISTS `{$table_name}` (\n";
		$thisQuery .= 	"	{$colObj->query_string}";
		$thisQuery .= 	"){$engineQry};\n\n";
		(new self)->initAction();
		self::$curr_query = $thisQuery;
		self::exec_status(self::$databaseObj->execute($thisQuery), $table_name, 'create');
	}


	public static function update($table_name, $action_function) {
		$colObj = new Column("update");
		$action_function($colObj);
		$thisQuery = "ALTER TABLE `{$table_name}` {$colObj->query_string}";
		(new self)->initAction();
		self::$curr_query = $thisQuery;
		self::exec_status(self::$databaseObj->execute($thisQuery), $table_name, 'update');
	}
	
	
	public static function index($table_name, $action_function) {
		$colObj = new Column($table_name);
		$action_function($colObj);
		(new self)->initAction();
		
		$query_arr = self::resolveValidIndexQuery($colObj->query_string, $table_name);
		if (!empty($query_arr)) {
			$querycount = count($query_arr);
			for ($i=0; $i<$querycount; $i+=1) {
				self::$curr_query = $curr_query = $query_arr[$i];
				self::exec_status(self::$databaseObj->execute($curr_query), $table_name, 'index');
			}
		}
	}
	
	
	public static function changesize($table_name, $action_function) {
		$colObj = new Column("change_size");
		$action_function($colObj);
		(new self)->initAction();
		$replace_str = preg_replace('/(`\w+`)/', "ALTER TABLE {$table_name} MODIFY COLUMN $1", $colObj->query_string);
		self::$curr_query = $replace_str;
		self::exec_status(self::$databaseObj->execute($replace_str), $table_name, 'size');
	}
	
	
	private static function exec_status($status, $name, $actor) {
		if (!$status) {
			echo "------------------Failed Query(ies)---------------------------------------------------<br>";
			echo self::$curr_query; 
			echo "<br><br>";
		}
	}
	
	
	private static function resolveValidIndexQuery($inQuery, $tab_name) {
		$queryArr = array();
		if ($inQuery != "") {
			$qExplode = explode(";", $inQuery);
			if (!empty($qExplode)) {
				$explcount = count($qExplode);
				for($k=0; $k<$explcount; $k+=1) {
					$current_q = $qExplode[$k];
					preg_match('/(\w+) ON/', $current_q, $matches);
					if (isset($matches[1])) {
						$indx_det = self::$databaseObj->check_index(self::$curr_db, $tab_name, $matches[1]);
						if (!empty($indx_det)) continue;
						$queryArr[] = "{$current_q};";
					}
				}
			}
		}
		return $queryArr;
	}
}