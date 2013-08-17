<?php
/*
 * docMe Beta v0.1
 * Renaud Tertrais
 * www.emaj.fr
 *
 */

if ($_POST['query']=='majSidebar'){
	session_start();
	$_SESSION['user']['sidebar']['width'] = $_POST['width'];
}
?>