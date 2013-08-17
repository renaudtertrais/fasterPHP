<?php
/*
 * docMe Beta v0.1
 * Renaud Tertrais
 * www.emaj.fr
 *
 */
?>
<?php session_start(); ?>
<?php include('doc.config.php');?>
<?php
$doc_content = '';
include('doc.functions.php');
if(!$_SESSION['user']['sidebar']['width']){
	$_SESSION['user']['sidebar']['width'] = '20%';
}
define ('SIDEBAR_WIDTH' , $_SESSION['user']['sidebar']['width']);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo DOC_TITLE; ?></title>
	<link rel="stylesheet" type="text/css" href="css/monokai.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<!-- js -->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/highlight.pack.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</head>

<body>
	<?php if(DOC_SIDEBAR):?>
		<div id="sidebar" style="width:<?php echo SIDEBAR_WIDTH; ?>">
			<?php the_sidebar(); ?>
		</div>
	<?php endif;// DOC_SIDEBAR ??>

	<div id="content-doc" <?php if(DOC_SIDEBAR) echo 'class="sidebar-on"';?>>
		<?php the_doc(); ?>
	</div><!-- #content-doc -->

</body>
</html>
