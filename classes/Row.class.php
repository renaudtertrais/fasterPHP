<?php //>
class Row{ 

	// Vars
	private $_table;
	private $_fields;
	private $_key;
	public $exists;	

	/* @function constructor of the class
	 * @param (string) $key 	: primary key of the table (to identify the row) (e.g. : 'id') (required)
	 * @param (mixed)  $value 	: value of the primary key
	 * @param (Table)  $table   : a Table object which contains the row (can be created here, e.g. : $user = new Row( 'pseudo' , 'bob' , new Table('users') ); )
	 * @return (Row) : this object
	 */
	public function __construct( $key , $value , $table ) {

		$this->_table = $table;

		$this->_key = $key ;
		
		$this->_fields = array_shift($this->_table->find( array( 'where'=>$key."='".$value."'" ) ) );

		if( $this->_fields != NULL ){
			$this->exists 	= true;
		}else{
			$this->exists 	= false;
		}
		return self;	
	}

	/* @function return the table object
	 * @return (Table) : the table object 
	 */
	public function getTable(){
		return $this->_table ;
	}
	/* @function change the table object
	 * @param (Table) $table : the table object
	 */
	public function setTable($table){
		$this->_table = $table ;
	}
	

	/* @function get a field value from the row
	 * @param (string) $field : field name
	 * @return (mixed) : value of the field
	 */
	public function get($field){
		return $this->_fields[$field];
	}
	/* @function get all the fields from the row
	 * @return (array) : fields of the row (name => value,...)
	 */
	public function getFields(){
		return $this->_fields;
	}
	
	/* @function set new value for the row and refresh() the row
	 * @param (array) $fields : fields to change (name => value,...)
	 */
	public function set($fields){
		$args['fields'] = $fields;
		$args['where'] = $this->_key . "='" . $this->get($this->_key) . "'" ;
		$this->_table->set($args);
		$this->refresh();
	}

	/* @function refresh the row's fields from the table
	 */
	public function refresh(){
		$this->_fields = array_shift($this->_table->find(array('where' => $this->_key . "='" . $this->get($this->_key) . "'")));
	}

	/* @function remove the row from the table and __destruct() the object
	 */
	public function remove(){
		$this->_table->remove($this->_key . "='" . $this->get($this->_key) . "'");
		$this->__destruct();
	}

	private function __destruct() {};
}
?>