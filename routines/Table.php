<?php
class Table {
	public function __construct() {}
	
    static function create($table_name, $action_function, $dbEngine="") { //PRIMARY KEY(Item_Id, Purchase_Id)
		$colObj 	= new Column("create");
		$engineQry 	= ($dbEngine != "") ? "ENGINE={$dbEngine}" : "";
		
		$action_function($colObj);
		$curr_db = $_SESSION['curr_db'];
		//preg_match_all('/PRIMARY KEY/', $colObj->query_string, $matches);
		//if (count($matches[0]) > 1) {
		//dbgarr('$matches123', $matches);
		//}
		
		
		$thisQuery  =   "";
		$thisQuery .= 	"CREATE TABLE IF NOT EXISTS `{$table_name}` (\n";
		$thisQuery .= 	"	{$colObj->query_string}";
		$thisQuery .= 	"){$engineQry};\n\n";
		
		if ($curr_db == "cornerstonelife_ieslifecomm") $curr_db = "cornerstonelife_ieslifecomm2";
		$databaseObj = new Db($curr_db);
		$execute_status = $databaseObj->execute($thisQuery);
		echo "execute_status === $execute_status <br><br> curr_db == $curr_db <br><br> $thisQuery<br><br><br><br><br><br>";
	}

	static function update($table_name, $action_function) {
		$colObj = new Column("update");
		$action_function($colObj);
		$thisQuery = "ALTER TABLE `{$table_name}` {$colObj->query_string}";
		//echo "thisQuery === $thisQuery\n\n";
	}
}