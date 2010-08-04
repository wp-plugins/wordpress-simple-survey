<div class="wrap">
	<div id="icon-edit-pages" class="icon32"></div><h2>Wordpress Simple Survey Results</h2>


	<hr />

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js" type="text/javascript"></script> 
	<script type="text/javascript">
	$(document).ready(function(){

		//Hide (Collapse) the toggle containers on load
		$(".togglebox1").hide(); 

		//Slide up and down on click
		$("#wpss_results h2").click(function(){
			$(this).next(".togglebox1").slideToggle("slow");
		});

	});
	</script>

	<?php // Grab all results from database
	global $wpdb;
	$table_name = $wpdb->prefix . 'wpss_quizTracking';
	$results = $wpdb->get_results( "SELECT results, time FROM ".$table_name );
	?>


	<div id="wpss_results">

	<?php
	foreach($results as $res){
		?>
		 
		<h2>Results - <?php echo $res->time;?>
		<?php 
			// Include Name 
			$namePos = strpos($res->results,"Name: ");
			echo '<span class="name">'.substr($res->results,$namePos,strpos($res->results,"Email: ")-$namePos);?></span></a></h2> 
		<div class="togglebox1"> 
			<div class="block"> 
				<p><?php 
					$outRes = str_replace('<h2>','<p><strong>',($res -> results));
					$outRes = str_replace('</h2>','</strong></p>',$outRes);
					$outRes = strip_tags($outRes, '<p><strong>');
					$outRes = str_replace('?','?<br />',$outRes);
					echo $outRes;
				?>
				</p> 
				<!--Content--> 
			</div> 
		</div> 

	<?php }
	?>

	</div>



</div>
