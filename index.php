<?php
	include("funcs.php");
	include("routines/Db.php");
	include("routines/Table.php");
	include("routines/Column.php");
	
	$databases = [
		'cornerstonelife_ieslifecomm',
		'cornerstonelife_ieslifehmrs'
	];
	
	if (!empty($databases)) {
		$dbcount = count($databases);
		for ($i=0; $i<$dbcount; $i+=1) {
			$curr_db = $databases[$i];
			
			$dbObj 	 = new Db($curr_db);
			$tab_arr = $dbObj->show_tables();
			$r_indx  = "Tables_in_{$curr_db}";
			
			foreach($tab_arr as $ky=>$vals) {
				$curr_table = $vals[$r_indx];
				$tab_desc = $dbObj->describe_table($curr_table);
				//$dbObj->dbgarr($curr_table, $tab_desc);
			}
		}
	}
	
	Table::create('makeme', function($table){
		$table->string('col_one', 2)->make_null();
		$table->int('col_tow', 2)->set_default("10,2");
		$table->float('col_three', 2);
	});
?>