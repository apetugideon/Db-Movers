<?php
class Db {
	private $host 	= "";
	private $user 	= "";
	private $pass	= "";
	private $dbname = "";
	private $conn	= null;
	
    public function __construct($dbname) {
		$this->resolveDb();
		$this->dbname = $dbname;
		$this->do_connection();
	}
	
	public function do_connection() {		
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
	
	
	public function show_indexes($curr_tab) {
		try {
            $qry  = "SHOW INDEXES FROM {$curr_tab}";
            $stmt = $this->conn->prepare($qry);
            return ($stmt->execute()) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
        } catch (PDOException $e) {
			$this->log_error($e);
        }
	}

	
	public function execute($in_query) {
        try {
            if ($in_query != "") {
                $stmt	= $this->conn->prepare($in_query);
                return $stmt->execute();
            }
        } catch (Exception $e) {
            $this->log_error($e);
        }
    }
	
	
	public function check_index($curr_db, $curr_tab, $index) {
		try {
            $qry  = "SELECT 1        
					 FROM INFORMATION_SCHEMA.STATISTICS
					 WHERE TABLE_SCHEMA = '{$curr_db}' 
					 AND TABLE_NAME='{$curr_tab}' 
					 AND INDEX_NAME='{$index}';";
            $stmt = $this->conn->prepare($qry);
            return ($stmt->execute()) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : array();
        } catch (PDOException $e) {
			$this->log_error($e);
        }
	}


	private function resolveDb() {
		$configObj = Entry::config();
		if (!empty($configObj)) {
			$this->host = (isset($configObj['db_host'])) ? trim($configObj['db_host']) : "";
			$this->user = (isset($configObj['db_user'])) ? trim($configObj['db_user']) : "";
			$this->pass = (isset($configObj['db_pass'])) ? trim($configObj['db_pass']) : "";
		}
	}
	
	
	protected function log_error($e) {
		//$e->getLine(), $e->getFile(), $e->getMessage();
	}
}
