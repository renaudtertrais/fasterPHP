<?php //>
class Table extends DataBase{

	// Vars
	private $_table;

	/* @function constructor of the class
	 * @param (string) $name 	: name of the table (required)
	 * @param (string) $db 		: database (optional)
	 * - @key (string) $host 	: host of the database (optional) default value in 'config.php'
	 * - @key (string) $login 	: login of the database (optional) default value in 'config.php'
	 * - @key (string) $password: password of the database (optional) default value in 'config.php'
	 * - @key (string) $database: name of the database (optional) default value in 'config.php'
	 * @return (Table) : this object
	 */
	public function __construct( $table, $db = array()){
		$db = array_merge(array( 'host' => DB_HOST , 'login' => DB_LOGIN , 'password' => DB_PASSWORD , 'database' => DB_NAME ), $db);
		
		$this->_table = $table;

		return parent::__construct( $db['database'], $db );	
	}

	/* @function return the table name
	 * @return (string) : the table name 
	 */
	public function getTable(){
		return $this->_table ;
	}
	/* @function change the table
	 * @param (string) $table : the table name
	 */
	public function setTable($table){
		$this->_table = $table ;
	}
	
	
	/* @function find rows in the database (SELECT query)
	 * @param (array) $args 	: arguments
	 * @key	(string) $fields 	: fields to select 			(optional) default : * (all)
	 * @key	(string) $where  	: ex : id < 5				(optional) 
	 * @key	(string) $orderBy	: order by specific fields  (optional)
	 * @key	(string) $order		: ASC or DESC 				(optional) default : DESC
	 * @key	(int) 	 $limit		: limit of the request 		(optional)
	 * @return (array) : indexed array of associative arrays
	 */
	public function find($args = array()){
		$args['table'] = $this->_table;
		return parent::find($args);
	}

	/* @function alias of 'find()' method but return only the first result
	 * @return (array) : associative array
	 */
	public function findOne($args = array()){
		$args['table'] = $this->_table;
		return parent::findOne($args);
	}

	/* @function add a row into a table
	 * @param (array) $values : associative array of the value of the new row (name => value, ... )
	 */
	public function add($values = array()){
		return parent::add( $this->_table , $values );
	}

	/* @function set new values for lines in a table
	 * @param (array)  $args 	: associative array which describe the SQL query
	 * - @key (array)  $fields 	: new values (name => value, ... )
	 * - @key (string) $where 	: condition(s) to specicify lines to change (e.g. : " id < '23' AND name = 'foo' ")	
	 */
	public function set($args = array()){
		$args['table'] = $this->_table;
		return parent::set($args);
	}

	/* @function remove lines in a table
	 * @param (array)  $args 	: associative array which describe the SQL query
	 * - @key (string) $where 	: condition(s) to specicify lines to remove (e.g. : " id < '23' AND name = 'foo' ")	
	 */
	public function remove($where = ''){
		$args['table'] = $this->_table;
		$args['where'] = $where;
		return parent::remove($args);
	}

}
?>