<?php
/*
 * docMe Beta v0.1
 * Renaud Tertrais
 * www.emaj.fr
 *
 */

/* @function read a file and return its documentation
 * @param (string) $file : path to the file
 * @return (array) : assoc array 
 */
function docThisFile( $file ){
	$rgxTpl 			= "/@template[ \t]*:[ \t]*([\w]*)/";										// 1
	$rgxClassName 		= "/^[ \t]*class[ \t]+([\w]+)[ \t]*(extends)*[ \t]*([\w]*)/";						// 1
	$rgxFunctions 		= "/^[ \t]*(public)*[ \t]*(static)*[ \t]*function[ \t]*([\w]*)/"; 			// 1 : type , 2 : static , 3 : name
	$rgxProperties		= "/(public)[ \t]*([$][\w]+)/";												// 1 : type , 2 : name
	$rgxFunctionDoc 	= "/@function[ \t]+([\w -=><,\.\/\\;\t]*)/";								// 1
	$rgxParams 			= "/@param[ \t]*\(([\w]*)\)[ \t]*([$\w]*)[ \t]*:([\w -=><,\.\/\\;\t]*)/"; 	// 1 : type , 2 : name , 3 : doc
	$rgxKeys 			= "/@key[ \t]*\(([\w]*)\)[ \t]*([$\w]*)[ \t]*:([\w -=><,\.\/\\;\t]*)/";		// 1 : type , 2 : name , 3 : doc
	$rgxReturn			= "/@return[ \t]*\(([\w]*)\)[ \t]*:([\w -=><,\.\/\\;\t]*)/";				// 1 : type , 2 : doc
	$rgxPHP 			= "/(<\?+(php))|(\?>)/";

	$file = file($file);

	$i=-1;
	$j=-1;
	$k=-1;
	$m=-1;

	// delete php tags
	$file = preg_replace($rgxPHP, '', $file);

	foreach ($file as $line) {
		//echo '<br />'.$line;
		// template ?
		if(preg_match_all($rgxTpl, $line, $result) ){
			$_doc['tpl'] = $result[1][0];	
		// class ?
		}else if(preg_match_all($rgxClassName, $line, $result) ){
			$_doc['class name'] = $result[1][0];
			$_doc['extends'] = $result[3][0];
		// function ?
		} else if(preg_match_all($rgxFunctionDoc, $line, $result) ){
			$i++;
			$j=-1;
			$_doc['functions'][$i]['doc']	= $result[1][0];
			/*
			if(empty($_doc['type'])){
				$_doc['type'] = 'functions';
			}
			*/
		// function info
		}else if (preg_match_all($rgxFunctions, $line, $result) ){
			$_doc['functions'][$i]['type'] 	= $result[1][0] ;
			$_doc['functions'][$i]['static'] = $result[2][0] ;
			$_doc['functions'][$i]['name'] 	= $result[3][0] ;
		// params ?
		}else if (preg_match_all($rgxParams, $line, $result) ){
			$j++;
			$m=-1;
			$_doc['functions'][$i]['params'][$j]['type'] = $result[1][0] ;
			$_doc['functions'][$i]['params'][$j]['name'] = $result[2][0] ;
			$_doc['functions'][$i]['params'][$j]['doc'] = $result[3][0] ;
		// param keys ?
		}else if(preg_match_all($rgxKeys, $line, $result) ){
			$m++;
			$_doc['functions'][$i]['params'][$j]['keys'][$m]['type'] = $result[1][0] ;
			$_doc['functions'][$i]['params'][$j]['keys'][$m]['name'] = $result[2][0] ;
			$_doc['functions'][$i]['params'][$j]['keys'][$m]['doc'] = $result[3][0] ;
		// return
		}else if(preg_match_all($rgxReturn, $line, $result) ){
			$_doc['functions'][$i]['return']['type'] = $result[1][0] ;
			$_doc['functions'][$i]['return']['doc'] 	= $result[2][0] ;
		// properties
		}else if(preg_match_all($rgxProperties, $line, $result) ){
			$k++;
			$_doc['properties'][$k]['type'] = $result[1][0];
			$_doc['properties'][$k]['name'] = $result[2][0];
		}
	}
	// auto affect class if no template and it is a class
	if( !$_doc['tpl'] && $_doc['class name']){
		$_doc['tpl'] = 'class';
	}

	return $_doc;
}
/* @function display the doc
 */
function the_doc(){
	if(isset($_POST['submit'])){
		echo 'search...';
	}else if(!empty($_GET['id'])) {
			global $doc;

			$_doc = array('');
			foreach ($doc as $section) {
				foreach ($section as $key => $value) {
					if($key === 'doc'){
						foreach ($value as $path) {
							$_doc[] = $path;
						}
					}
				}
			}

			$id = intval($_GET['id']); 
			if( $id < count($_doc) && $id > 0 ){
				echo $_doc[$id];
				global $doc_content;
				$doc_content = docThisFile($_doc[$id]);
				//$doc_content['file_name'] = file_name($_doc[$id]);
				if( file_exists('tpl/tpl.'.$doc_content['tpl'].'.php') ){
					include('tpl/tpl.'.$doc_content['tpl'].'.php');
				}else{
					include('tpl/tpl.default.php');
				}
			}else{
				include(DOC_HOME);
			}
	}else{
		include(DOC_HOME);
	}
	
}
/* @function documentation incomming...
*/
function the_sidebar(){
	include('sidebar.php');
}
function the_doc_nav(){
	global $doc;
	$nb = 1;
	foreach ($doc as $value) {
		$nb = display_nav($value,$nb);
	}
}
 
/* @function documentation incomming...
*/
function display_nav($array,$nb){
	echo '<ul>';
	echo '<li class="parent"><h4>' . $array['title'] . '</h4>';
	echo '<ul>';	
	foreach ($array['doc'] as $path) {
		echo '<li class="path"><a href="index.php?id='.$nb.'">'.pathinfo($path,2).'</a></li>';
		$nb++;
	}
	echo '</ul>';
	echo '</li>';
	echo '</ul>';
	return $nb;
}

?>