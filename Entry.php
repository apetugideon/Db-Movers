<?php
class Entry {
	
	public function __construct() {}
	
	
	static public function config() {
		$outArr = array();
		$conStr = explode("\n", file_get_contents("config.txt"));
		if (!empty($conStr)) {
			for ($t=0; $t<count($conStr); $t+=1) {
				preg_match('/(\w+)=([-A-Za-z0-9!@$%~^&*+=_, ]+)/', $conStr[$t], $matches);
				if (!empty($matches)) $outArr[$matches[1]] = $matches[2];
			}
		}
		return $outArr;
	} 
	
	
	static public function file_inclusion() {
		spl_autoload_register(function ($class_name) {
			preg_match('/\bAdd\w+\b/', $class_name, $match);
			(empty($match)) ? include("routines/{$class_name}.php") : include("routines/AddColumn.php");
		});
	}
	
	
	static public function generate() {
		$config = self::config();
		$databases = (isset($config['databases'])) ? explode(",", trim($config['databases'])) : "";
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
					$tabIndx = Utilities::resolve_indexes($dbObj->show_indexes($curr_table));
					Utilities::genMigration($tab_desc, $curr_table, $curr_db, $tabIndx);
				}
			}
		} else {
			echo "Warning : No Database to work with!";
		}
	}
	
	
	static public function migrate() {
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
	}
	
	
	static public function dbgarr($desc, $arrval) {
		echo "<pre>{$desc}";
		print_r($arrval);
		echo "</pre>";
		echo "<br>";
	}
}
	