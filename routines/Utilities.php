<?php

class Utilities {
	public static $dataTypeList = array();
	
	public function __construct() {}

	public static function genMigration($tab_desc=array(), $curr_table, $curr_db) {
		$final_code_text = "";
		if (!empty($tab_desc)) {
			
			$pri_keyArr = array();
			foreach($tab_desc as $tabval) {
				$col_key  = $tabval['Key'];
				$col_name = $tabval['Field'];
				if ($col_key == "PRI") $pri_keyArr[] = $col_name;
			}

			$uniqueStr = (count($pri_keyArr) > 1) ? "	\$column->unique(['" . implode("','", $pri_keyArr) . "']);\n" : "";
			foreach($tab_desc as $tabval) {
				$col_name = $tabval['Field'];
				$col_type = $tabval['Type'];
				$is_null  = $tabval['Null'];
				$col_key  = $tabval['Key'];
				$def_val  = $tabval['Default'];
				$col_xtra = $tabval['Extra'];

				$res 	  = (new self())->resolveDataType($col_type);
				$res_type = $res['res_type'];
				$col_size = $res['col_size'];
				
				if (!in_array($res_type, self::$dataTypeList)) self::$dataTypeList[] = $res_type; 

				$def_key = "";
				if (($col_key == "PRI") && (count($pri_keyArr) == 1)) $def_key = "->primary()";
				if (($col_key == "PRI") && (count($pri_keyArr) > 1)) {
					$def_key = "";
				}
				
				if ($res_type == "varchar") $res_type = "string";
				$col_size_new = (count(explode(",", $col_size)) > 1) ? "'{$col_size}'" : $col_size;
				$col_size_str = ($col_size != "") ? ", {$col_size_new}" : "";
				$make_null 	  = ($is_null == "YES") ? "->null()" : "";
				$set_default  = ($def_val != "") ? "->default('{$def_val}')" : "";
				$incrmnt_str  = ($col_xtra == "auto_increment" && $res_type == "int") ? "->increment()" : ""; 

				$curr_line    = "\$column->{$res_type}('{$col_name}'{$col_size_str}){$make_null}{$set_default}{$incrmnt_str}{$def_key};";
				$final_code_text .= "	{$curr_line}\n";
			}
		}
		
		$code_text  = "";
		$code_text .= "<?php\n";
		$code_text .= "Table::create('{$curr_table}', function(\$column) {\n";
		$code_text .= "{$final_code_text}{$uniqueStr}";
		$code_text .= "});";

		$cls_name = ucfirst($curr_table);
		self::writeFile("Migrations/{$curr_db}/{$cls_name}.php", $code_text);
	}

	public static function writeFile($file_name, $file_text) {
		$fileObj = fopen($file_name, "w") or die("Unable to open file!");
		fwrite($fileObj, $file_text);
		fclose($fileObj);
	}

	public function resolveDataType($col_type) {
		preg_match('/(\w+)\((\d{1,4}(,\d{1,4})?)\)/', $col_type, $matches);
		$retarr['res_type'] = (empty($matches)) ? $col_type : $matches[1];
		$retarr['col_size'] = (empty($matches)) ? "" : $matches[2];
		return $retarr;
	}
}
	
	