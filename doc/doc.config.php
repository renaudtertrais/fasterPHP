<?php
/*
 * docMe Beta v0.1
 * Renaud Tertrais
 * www.emaj.fr
 *
 */

// CONSTANTS
define( 'DOC_SIDEBAR' 	, true );
define( 'DOC_HOME' 		, 'home.php' );
define( 'DOC_TITLE' 	, 'fasterPHP Beta v0.1' );

// CONFIG
/* organize you doc here
 * allowed filds :
 * @key (string) 'title'  	: title of section (not necessary)
 * @key (array) 'doc'	  	: array of string (path of files)
 * @key (array) 'children' 	: need more deep ? (title required)
 */
$doc = array(
	array(
		'title' => 'classes',
		'doc' 	=> array(
			'../classes/Database.class.php',
			'../classes/Table.class.php',
			'../classes/Row.class.php',
			'../classes/Upload.class.php',
			'../classes/Mail.class.php'
		)
	)
);



?>