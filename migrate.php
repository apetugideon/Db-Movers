<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

include("funcs.php");
include("routines/Db.php");
include("routines/Utilities.php");
include("routines/Table.php");
include("routines/AddColumn.php");
include("routines/Column.php");

$migration_dir = scandir("Migrations");
if (!empty($migration_dir)) {
	for($j=0; $j<count($migration_dir); $j+=1) {
		$curr_db = $migration_dir[$j];
		if ($curr_db == '.' || $curr_db == '..') continue;
		if (is_dir("Migrations/{$curr_db}")) {
			$migrations = scandir("Migrations/{$curr_db}");
			if (!empty($migrations)) {
				$migratn_count = count($migrations);
				for($i=0; $i<$migratn_count; $i+=1) {
					$cur_table = $migrations[$i];
					if ($cur_table == '.' || $cur_table == '..') continue;
					$_SESSION['curr_db'] = $curr_db;
					include("Migrations/{$curr_db}/{$cur_table}");
				}
			}
		}
	}
}