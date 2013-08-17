<?php
/*
 * docMe Beta v0.1
 * Renaud Tertrais
 * www.emaj.fr
 *
 */
?>
<?php global $doc_content; ?>
<h1><?php echo $doc_content['file_name'];?></h1>
	
<!-- Methods -->
	<h2>Functions</h2>
	<!-- summary -->
		<table class="summary">
			<?php for ($i=0; $i < count($doc_content['functions']); $i++) :?>
				<tr>
					<td>
						<a href="#doc_<?php echo $doc_content['functions'][$i]['name'];?>">
							<?php echo $doc_content['functions'][$i]['type'];?> 
							<?php echo $doc_content['functions'][$i]['static'];?>
							<strong> <?php echo $doc_content['functions'][$i]['name'];?></strong>
						</a>
					</td>
					<td class="doc"><em><?php echo $doc_content['functions'][$i]['doc'];?></em></td>
				</tr>
			<?php endfor; //summary?>
		</table>
	<?php for ($i=0; $i < count($doc_content['functions']); $i++) :?>
	<table class='method'>
		<tr>
			<td colspan="4">
				<h3 id="doc_<?php echo $doc_content['functions'][$i]['name'];?>">
					<?php echo $doc_content['functions'][$i]['name'];?>
				</h3>
			</td>
		</tr>
		<tr>
			<td colspan="4"><em><?php echo $doc_content['functions'][$i]['doc'];?></em></td>
		</tr>
	<!-- params -->
		<?php for ($j=0; $j < count($doc_content['functions'][$i]['params']); $j++) :?>
			<tr>
				<td>param <?php echo $j+1;?></td>
				<td><strong><?php echo $doc_content['functions'][$i]['params'][$j]['name'];?></strong></td>
				<td class="type"><?php echo $doc_content['functions'][$i]['params'][$j]['type'];?></td>
				<td class="doc"><em><?php echo $doc_content['functions'][$i]['params'][$j]['doc'];?></em></td>
			</tr>
		<!-- keys -->
			<?php for ($k=0; $k < count($doc_content['functions'][$i]['params'][$j]['keys']); $k++) :?>
				<tr>
					<td>- key</td>
					<td>'<?php echo str_replace('$','', $doc_content['functions'][$i]['params'][$j]['keys'][$k]['name']);?>'</strong></td>
					<td class="type">=> <?php echo $doc_content['functions'][$i]['params'][$j]['keys'][$k]['type'];?></td>
					<td class="doc"><em><?php echo $doc_content['functions'][$i]['params'][$j]['keys'][$k]['doc'];?></em></td>
				</tr>
				
			<?php endfor; //keys ?>
		<?php endfor; //params ?>
	<!-- return -->
		<?php if( $doc_content['functions'][$i]['return']['type'] != ''): ?>
			<tr>
				<td colspan="2">return</td>
				<td class="type"><?php echo $doc_content['functions'][$i]['return']['type'];?></td>
				<td class="doc"><em><?php echo  $doc_content['functions'][$i]['return']['doc'];?></em></td>
			</tr>
		<?php endif;?>
	</table>
	<?php endfor; // methods ?>