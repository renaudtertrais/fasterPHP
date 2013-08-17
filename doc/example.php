<?php 
// @template : class
class MyClass
{
	// Properties
	private 	$_myPrivateProperty ;
	public 		$myPublicProperty ;
	protected 	$_myProtectedProperty;

	/* @function constructor of the class
	 * @param (int) $param : a param to construct the object
	 */
	public function __construct( $param ){
		// ...
	}

	/* @function example of a public static method
	 * @param (array) 	$paramArray   : assoc array with the fields of the shape
	 * - @key (string) 	$foo  	: foo ... 
	 * - @key (int) 	$bar 	: bar ...
	 * @return (array) : array ...
	 */
	public static function MyPublicStaticMethod( $paramArray ){
		// ...
	}

	/* @function setter of _myPrivateProperty
	 * @param (mixed) $value : fields to add/update
	 */
	public function set( $value ){
		// ...
	}

}
?>