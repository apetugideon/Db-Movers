<?php
class Db {
	private $host 	= "localhost";
	private $user 	= "root";
	private $pass	= "";
	private $dbname = "";
	private $conn	= null;
	
	
    public function __construct($dbname) {
		$this->dbname = $dbname;
		$this->do_connection();
	}


	public function do_connection() { //Parameter from service environmental variables
		try {
			$this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass, array(
				PDO::ATTR_PERSISTENT => true
			));
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			$this->log_error($e);
		}
	}


	public function show_tables() {
		try {
            $qry  = "SHOW TABLES";
            $stmt = $this->conn->prepare($qry);
            return ($stmt->execute()) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
        } catch (PDOException $e) {
			$this->log_error($e);
        }
	}
	
	
	public function describe_table($curr_tab) {
		try {
            $qry  = "DESCRIBE {$curr_tab}";
            $stmt = $this->conn->prepare($qry);
            return ($stmt->execute()) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
        } catch (PDOException $e) {
			$this->log_error($e);
        }
	}


	//EXTRACT ALL PRIMARY AND FOREIGN KEYS IN A DATABASE WRT THEIR TABLES
    public function CURR_USER_ID() {
        try {
            $qry  = "select table_name, column_name, referenced_table_name, referenced_column_name
                    FROM information_schema.key_column_usage
                    WHERE (referenced_table_name is not null)
                    AND (column_name='modified_by' OR column_name='created_by')
                    AND (CONSTRAINT_SCHEMA='$this->dbname') ";
            $stmt = $this->conn->prepare($qry);
            return ($stmt->execute()) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
        } catch (PDOException $e) {
			$this->log_error($e);
        }
    }
	
	
	protected function log_error($e) {
		//$e->getLine(), $e->getFile(), $e->getMessage();
	}
	
	
	public function dbgarr($desc, $arrval) {
		echo "<pre>{$desc}";
		print_r($arrval);
		echo "</pre>";
		echo "<br>";
	}
}
