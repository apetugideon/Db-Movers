<?php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');


	include("funcs.php");
	include("routines/Db.php");
	include("routines/Table.php");
	include("routines/AddColumn.php");
	include("routines/Column.php");
	

	$databases = ['cornerstonelife_ieslifecomm','cornerstonelife_ieslifehmrs'];
	$databases = array('drivers_hiringdb','petty_cashee_trans');
	

	if (!empty($databases)) {
		$dbcount = count($databases);
		for ($i=0; $i<$dbcount; $i+=1) {
			$curr_db = $databases[$i];

			if (!is_dir("Migrations/{$curr_db}")) {
				mkdir("Migrations/{$curr_db}");
			}
			
			$dbObj 	 = new Db($curr_db);
			$tab_arr = $dbObj->show_tables();
			$r_indx  = "Tables_in_{$curr_db}";
			
			foreach($tab_arr as $ky=>$vals) {
				$curr_table = $vals[$r_indx];
				genMigration($dbObj->describe_table($curr_table), $curr_table, $curr_db);	
			}
		}
	}


	function genMigration($tab_desc=array(), $curr_table, $curr_db) {
		$cls_name = ucfirst($curr_table);
		$cls_file = fopen("Migrations/{$curr_db}/{$cls_name}.php", "w") or die("Unable to open file!");

		$final_code_text = "";
		if (!empty($tab_desc)) {
			foreach($tab_desc as $tabval) {
				$col_name = $tabval['Field'];
				$col_type = $tabval['Type'];
				$is_null  = $tabval['Null'];
				$col_key  = $tabval['Key'];
				$def_val  = $tabval['Default'];
				$col_xtra = $tabval['Extra'];

				preg_match('/(\w+)\((\d{1,4}(,\d{1,4})?)\)/', $col_type, $matches);
				$res_type = (empty($matches)) ? $col_type : $matches[1];
				$col_size = (empty($matches)) ? "" : $matches[2];

				$def_key = "";
				if ($col_key == "PRI") $def_key = "->primary()";
				if ($res_type == "varchar") $res_type = "string";
				$col_size_new = (count(explode(",", $col_size)) > 1) ? "'{$col_size}'" : $col_size;
				$col_size_str = ($col_size != "") ? ", {$col_size_new}" : "";
				$make_null 	  = ($is_null == "YES") ? "->null()" : "";
				$set_default  = ($def_val != "") ? "->default('{$def_val}')" : "";
				$incrmnt_str  = ($col_xtra == "auto_increment" && $res_type == "int") ? "->increment()" : ""; 

				$curr_line    = "\$table->{$res_type}('{$col_name}'{$col_size_str}){$make_null}{$set_default}{$incrmnt_str}{$def_key};";
				$final_code_text .= "	{$curr_line}\n";
			}
		}

		$code_text = "";
		$code_text .= "<?php\n";
		$code_text .= "Table::create('{$curr_table}', function(\$table) {\n";
		$code_text .= "{$final_code_text}";
		$code_text .= "});";

		fwrite($cls_file, $code_text);
		fclose($cls_file);
	}
	

	Table::create('makeme', function($table) {
		$table->string('col_one', 2)->null();
		$table->string('testing', 22);
		$table->int('col_tow', 2)->default("10,2");
		$table->float('col_three', 2);
		$table->date('date');
		$table->datetime('mydatetime')->default("0000-00-00");
	});


	Table::update('makeme', function($table){
		$table->string('calid', 2)->null();
		$table->string('skido', 22);
		$table->float('plouse', "14, 6");
	});