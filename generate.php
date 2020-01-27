<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

include("funcs.php");
spl_autoload_register(function ($class_name) {
	include "routines/" . $class_name . '.php';
});

$databases = ['cornerstonelife_ieslifecomm','cornerstonelife_ieslifehmrs'];
//$databases = array('drivers_hiringdb','petty_cashee_trans');

if (!empty($databases)) {
	$dbcount = count($databases);
	for ($i=0; $i<$dbcount; $i+=1) {
		$curr_db = $databases[$i];
		
		if (!is_dir("Migrations")) mkdir("Migrations");
		if (!is_dir("Migrations/{$curr_db}")) mkdir("Migrations/{$curr_db}");
		
		$dbObj 	 = new Db($curr_db);
		$tab_arr = $dbObj->show_tables();
		$r_indx  = "Tables_in_{$curr_db}";
		foreach($tab_arr as $ky=>$vals) {
			$curr_table = $vals[$r_indx];
			$tab_desc = $dbObj->describe_table($curr_table);
			Utilities::genMigration($tab_desc, $curr_table, $curr_db);
		}
	}
}