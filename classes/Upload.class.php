<?php //>

class Upload{

	private $_error; 
	private $_fields;

	/* @function constructor of the class
	 * @param (array)  	$file 		: a $_FILES element (required)
	 * @param (int)  	$sizeMax 	: maximum size (Mo) of the file (optional) default : 5
	 * @param (array)  	$type   	: allowed extentions (optional) default : array('.jpg','.gif','.png','.jpeg')
	 * @return (Upload) : this object
	 */
	public function __construct( $file = array() , $sizeMax = 5, $types = array('.jpg','.gif','.png','.jpeg') ) {

		$sizeMax *= 1048576;

		$filename				= $file['name'];
		$this->_fields['path']	= $file['tmp_name'];
		$ext 					= strtolower(substr($filename, strpos($filename,'.'), strlen($filename)-1));
		$this->_fields['type']	= str_replace(".","",$ext);
		if($this->get('type') == 'jpeg'){
			$this->_fields['type'] = 'jpg';
		}
		$this->_fields['name']	= str_replace($ext,"",$filename);
		$this->_fields['size']	= filesize($file['tmp_name']);
		
		$this->_fields['max']	= $sizeMax;
		$this->_fields['ext']	= implode(', ',$types);

		// correct format ?
		$this->_error['type'] = !in_array($ext,$types);
	
		// correct size ?
		$this->_error['size'] = $this->get('size') > $sizeMax;

		return self;
	}

	/* @function get a specific meta form the upload
	 * @param (string) $meta : a upload meta available (name, path, type (curent type), size, max, ext(allowed types))
	 * @return (mixed) : the meta value
	 */
	public function get($meta){
		return $this->_fields[$meta];
	}

	/* @function get an error on the upload
	 * @param (string) $error : a upload error available (size, type))
	 * @return (bool) : the error value (TRUE : error, FALSE : no error)
	 */
	public function error($error){
		return $this->_error[$error];
	}

	/* @function move the file and rename it
	 * @param (string) $path 	: new path (required)
	 * @param (string) $newname	: new name of the file (optional)
	 * @return (bool) : return true if no error
	 */
	public function moveTo( $path , $newname = $this->_fields['name']){

		$this->_error['write'] = !is_writable($path);

		if($this->_error['write']) {
			return false;
		}else{
			$this->_error['move'] = !move_uploaded_file( $this->get('path') , $path . $newname . '.' .  $this->_fields['type']);
			if( $this->_error['move'] ){
				return false;
			}else{
				$this->_fields['path'] = $path . $newname . '.' . $this->_fields['type'];
				return true;
			}
		}
	}

}

?>