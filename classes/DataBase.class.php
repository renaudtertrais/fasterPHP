<?php
// @template : class
class DataBase extends PDO{

	// Vars 
	private $_query;
	private $_db;

	/* @function constructor of the class
	 * @param (string) $name 	: name of the database (optional) default value in 'config.php'
	 * @param (string) $db 		: database (optional)
	 * - @key (string) $host 	: host of the database (optional) default value in 'config.php'
	 * - @key (string) $login 	: login of the database (optional) default value in 'config.php'
	 * - @key (string) $password: password of the database (optional) default value in 'config.php'
	 * @return (Database) : this object
	 */
	public function __construct($name = DB_NAME, $db = array()){
		$db = array_merge(array( 'host' => DB_HOST , 'login' => DB_LOGIN , 'password' => DB_PASSWORD ), $db);

		$host 		= $db['host'];
		$log 		= $db['login'];
		$pwd 		= $db['password'];
		$this->_db 	= $name;

		try{
			return parent::__construct('mysql:host='.$host.';dbname='.$this->_db , $log , $pwd );
		}catch (PDOException $e) {
		    echo 'Connexion failed : ' . $e->getMessage();
		}
	}
	
	/* @function return the database name
	 * @return (string) : the database name 
	 */
	public function getDataBase(){
		return $this->_db ;
	}
	/* @function change the database
	 * @param (string) $db : the database name
	 */
	public function setDataBase($db){
		self::__construct(array('database'=>$db));
	}


	
	/* @function find rows in the database (SELECT query)
	 * @param (array) $args 	: arguments
	 * @key (string) $table		: table in the data base 	(required)
	 * @key	(string) $fields 	: fields to select 			(optional) default : * (all)
	 * @key	(string) $where  	: ex : id < 5				(optional) 
	 * @key	(string) $orderBy	: order by specific fields  (optional)
	 * @key	(string) $order		: ASC or DESC 				(optional) default : DESC
	 * @key	(int) 	 $limit		: limit of the request 		(optional)
	 * @return (array) : indexed array of associative arrays
	 */
	public function find($args = array()){
		$defaut_args = array('table' => '' , 'fields' =>  '*' , 'where' => '', 'orderBy' => '', 'order' => 'ASC', 'limit' => '');
		$args = array_merge($defaut_args,$args);

		$this->_query = 'SELECT ' . $args['fields'] . ' FROM ' . $args['table'] ;

		if ($args['where'] 		!= '')	$this->_query  .= ' WHERE ' 	. $args['where'] ;
		if ($args['orderBy'] 	!= '')	$this->_query  .= ' ORDER BY '	. $args['orderBy'] . ' ' . $args['order'] ;
		if ($args['limit'] 		!= '')	$this->_query  .= ' LIMIT '		. $args['limit'] ;

		$array = $this->preExe();
		if( count($array[0]) == 2){
			$i=0;
			foreach ($array as $value) {
				$array[$i] = $value[0];
				$i++;
			}
		}
		return $array ;
	}
	/* @function alias of 'find()' method but return only the first result
	 * @return (array) : associative array
	 */
	public function findOne($args){
		$lines = $this->find($args);
		$return = $lines[0];
		return $return;
	}

	/* @function add a row into a table
	 * @param (string) $table : name of the table
	 * @param (array) $values : associative array of the value of the new row (name => value, ... )
	 */
	public function add( $table = '' , $values = array() ){

		$this->_query = 'INSERT INTO ' . $table;

		if( $this->isAssocArray( $values ) ){
			$this->_query .= ' ( '. implode( ',' , array_keys( $values ) ) . ' ) ';
		}
		$this->_query .= ' VALUES ( ' . $this->arrayToString( array_values( $values ) ) . ' ) ';

		return $this->preExe();
	}

	/* @function set new values for lines in a table
	 * @param (array)  $args 	: associative array which describe the SQL query
	 * - @key (string) $table 	: name of the table
	 * - @key (array)  $fields 	: new values (name => value, ... )
	 * - @key (string) $where 	: condition(s) to specicify lines to change (e.g. : " id < '23' AND name = 'foo' ")	
	 */
	public function set($args = array()){
		$defaut_args = array('table' => '' , 'fields' =>  '' , 'where' => '' );
		$args = array_merge($defaut_args,$args);

		$this->_query  = 'UPDATE ' . $args['table'];
		$this->_query .= ' SET ' . $this->arrayAssocToString($args['fields']) ;
		if ($args['where'] != '') $this->_query .= ' WHERE '.$args['where'];

		return $this->preExe();
	}

	/* @function remove lines in a table
	 * @param (array)  $args 	: associative array which describe the SQL query
	 * - @key (string) $table 	: name of the table
	 * - @key (string) $where 	: condition(s) to specicify lines to remove (e.g. : " id < '23' AND name = 'foo' ")	
	 */
	public function remove($args = array()){
		$defaut_args = array('table' => '' , 'where' => '' );
		$args = array_merge($defaut_args,$args);

		$this->_query  = 'DELETE FROM ' . $args['table'];
		$this->_query .= ' WHERE '.$args['where'];

		return $this->preExe();
	}

	/* @function return the last sql query
	 * @return (string) : the last sql query
	 */
	public function lastQuery(){
		return $this->_query;
	}
	/* prepare and execute the sql query
	*/
	private function preExe(){
		$pre = $this->prepare($this->_query);
		$pre->execute();
		return $pre->fetchAll();
	}
	/* convert an array to a string for sql query
	 */
	private function arrayToString($array){
		$i = 0;
		$string = '';
		foreach ($array as $value) {
			if ($i > 0) $string .= ', ';
			if (preg_match('#', $value)){
				$string .= str_replace('#', '', $value);
			}else{
				$string .= "'".$value."'";
			}
			$i++;
		}
		return $string;
	}
	/* convert an array to a string for sql query
	*/
	private function arrayAssocToString($array){
		$i = 0;
		$string = '';
		foreach ($array as $key => $value) {
			if ($i > 0) $string .= ', ';
			if (preg_match('#', $value)){
				$string .= str_replace('#', '', $value);
			}else{
				$string .= $key ."='".$value."'";
			}
			$i++;
		}
		return $string;
	}
	/*  detect if an array is associative or indexed
	*/
	private function isAssocArray($array = array()){
	    if(!is_numeric(array_shift(array_keys($array)))){
	        return true;
	    }
    	return false;
	}
}
?>