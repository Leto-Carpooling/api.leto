<?php



class DbManager implements DatabaseInterface{
    private $dbConnection;
    private $dbName;
    private $dbHost;
    private $rows;
    private $statements = [];
	private $withOptions = false;
	private $fetchAll = true;

    /**
     * @param bool $options - to pass to the PDO connection
     */
    public function __construct($options = false){
		$this->dbHost = "localhost"; //getenv('DB_HOST');
        $this->dbPort = "3306"; //getenv('DB_PORT');
        $this->dbName   = "leto_db"; //getenv('DB_DATABASE');
        $this->withOptions = $options;
    }

	/**
	 * Connects to the database using the information in the .env folder.
	 *
	 * @return mixed
	 */
	public function connect() {
		if($this->dbConnection != null){
			$user = "root";//getenv('DB_USERNAME');
			$pass = "";//getenv('DB_PASSWORD');
			$dsn = "mysql:host=$this->dbHost;port=$this->dbPort;dbname=$this->dbName";
			$options = [];
			echo "username: $user, password: $pass";

			if(!$this->withOptions){
				$options = [ 
					PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES => false
			];
			}

			try {
				$this->dbConnection = new \PDO(
					$dsn,
					$user,
					$pass, 
					$options
				);
			} 
			catch (\PDOException $e) {
				exit($e->getMessage());
			}
		}
	}
	
	/**
	 *
	 * @param string $table 
	 * @param array $columns 
	 * @param array $condition_columns 
	 * @param array $condition_values 
	 * @param string $operator 
	 *
	 * @return mixed
	 */
	public function selectFromTable($table, $columns, $condition_columns, $condition_values, $operator = 'and') {
		$this->connect();

		$condition_string = "";
		foreach($condition_columns as $column){
			if($condition_string != ""){
				$condition_string .= " $operator ";
			}
			$condition_string .= " $column = ?";
		}

		$sql = "SELECT" . implode ($columns, ", ") ." from `$table` where $condition_string";
            $stmt = $this->dbConnection->prepare($sql);

            if($stmt->execute($condition_values)){
                $result = ($this->fetchAll)? $stmt->fetchAll() : $stmt->fetch();
                $return = $result;
            }
            else{
                $return = false;
            }

			return $return;
	}
	
	/**
	 *
	 * @param string $table 
	 * @param array $columns 
	 * @param array $values 
	 *
	 * @return int
	 */
	public function insertIntoTable($table, $columns, $values) {
		$sql = "INSERT INTO `$table`(". implode($columns, ", "). ") values (". $this->buildInsertPlaceholders(count($values)) .")";

		$this->connect();
		$statement = $this->dbConnection->prepare($sql);
		$newRowId = -1;

		if($statement->execute($values)){
			$newRowId = $this->dbConnection->lastInsertId();
		}
		
		return $newRowId;
	}
	
	/**
	 *
	 * @param mixed $table 
	 * @param mixed $condition_columns 
	 * @param mixed $operator 
	 *
	 * @return mixed
	 */
	public function deleteFromTable($table, $condition_columns, $operator = 'and') {
	}
	
	/**
	 *
	 * @param mixed $sql 
	 *
	 * @return mixed
	 */
	public function rawSql($sql) {
		
	}

	public function makeDatabase($sql){
		$this->connect();
		$statement = $this->dbConnection->prepare($sql);
		if($statement->execute()){
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 *
	 * @return mixed
	 */
	public function close() {
		$this->dbConnection = null;
	}

	/**
	 * @param int $number
	 * Creates the place holder string
	 */
	private function buildInsertPlaceholders($number){
		$placeholders = str_repeat("?,", $number);
		return substr($placeholders, 0, strlen($placeholders) - 1);
	}
	/**
	 * 
	 * @return bool
	 */
	function getFetchAll() {
		return $this->fetchAll;
	}
	
	/**
	 * 
	 * @param bool $fetchAll 
	 * @return DbManager
	 */
	function setFetchAll($fetchAll): self {
		$this->fetchAll = $fetchAll;
		return $this;
	}
}

?>