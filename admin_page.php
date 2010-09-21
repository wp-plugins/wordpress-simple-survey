<?php
	// Update Options
	if($_POST['wpss_hidden'] == 'Y') {
		
		//Form data sent, update database (adds slashes) w POST, then local variable
		$quizTitle	= htmlentities($_POST['wpss_quizTitle'],ENT_QUOTES);
		update_option('wpss_quizTitle', $quizTitle);
		$quizTitle	= stripslashes(get_option('wpss_quizTitle'));

		$quizIntro	= htmlentities($_POST['wpss_quizIntro'],ENT_QUOTES);
		update_option('wpss_quizIntro', $quizIntro);
		$quizIntro	= stripslashes(get_option('wpss_quizIntro'));

		$quizOutro	= htmlentities($_POST['wpss_quizOutro'],ENT_QUOTES);
		update_option('wpss_quizOutro', $quizOutro);
		$quizOutro	= stripslashes(get_option('wpss_quizOutro'));
	
		$ranges		= htmlentities($_POST['wpss_ranges'],ENT_QUOTES);
		update_option('wpss_ranges', $ranges);
		$ranges		= stripslashes(get_option('wpss_ranges'));
				
		$numQuestions = htmlentities($_POST['wpss_numQuestions'],ENT_QUOTES);
		update_option('wpss_numQuestions', $numQuestions);	
		$numQuestions = stripslashes(get_option('wpss_numQuestions'));
				
		$quizTrack	= htmlentities($_POST['wpss_quizTrack'],ENT_QUOTES);
		update_option('wpss_quizTrack', $quizTrack);
		$quizTrack	= stripslashes(get_option('wpss_quizTrack'));	

		$sendEmail	= htmlentities($_POST['wpss_sendEmail'],ENT_QUOTES);
		update_option('wpss_sendEmail', $sendEmail);	
		$sendEmail	= stripslashes(get_option('wpss_sendEmail'));
		
		$quizAuto	= htmlentities($_POST['wpss_auto'],ENT_QUOTES);
		update_option('wpss_auto', $quizAuto);
		$quizAuto 	= stripslashes(get_option('wpss_auto'));
		
		$mailfrom	= htmlentities($_POST['wpss_mailfrom'],ENT_QUOTES);
		update_option('wpss_mailfrom', $mailfrom);
		$mailfrom 	= stripslashes(get_option('wpss_mailfrom'));

		$emailResponce	= htmlentities($_POST['wpss_emailResponce'],ENT_QUOTES);
		update_option('wpss_emailResponce', $emailResponce);
		$emailResponce	= stripslashes(get_option('wpss_emailResponce'));
		if(!$emailResponce) $emailResponce = "Thanks for taking the [quiztitle],\r\nYou scored [score] and were routed to [routed].\r\nYour answers:\r\n[answers]\r\nDo not reply to this email address. This is an auto-generated message.";
				
		/* Save/Grab Questions */
		$questionsArray = array();
		for($n=0;$n<$numQuestions;$n++){
			$thisQuest = "wpss_questions_" . $n;
			$questionsArray[$n] = $_POST[$thisQuest];
		}
		update_option('wpss_questionsArray', $questionsArray);
		$questionsArray = get_option('wpss_questionsArray');
		if(!$numQuestions)$numQuestions++;//increment on activation
		for($n=0;$n<$numQuestions;$n++){
			$questionsArray[$n] = stripslashes($questionsArray[$n]);
		}

		?>
		<div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
		<?php
	} 
	else {
		// Normal Page Display
		// get saved values, remove slashes
		$ranges			= stripslashes(get_option('wpss_ranges'));
		$quizTitle		= stripslashes(get_option('wpss_quizTitle'));
		$quizIntro		= stripslashes(get_option('wpss_quizIntro'));
		$quizOutro		= stripslashes(get_option('wpss_quizOutro'));
		$numQuestions	= stripslashes(get_option('wpss_numQuestions'));
		$sendEmail		= stripslashes(get_option('wpss_sendEmail'));
		$quizTrack		= get_option('wpss_quizTrack');
		$quizAuto		= get_option('wpss_auto');
		$mailfrom		= stripslashes(get_option('wpss_mailfrom'));
		$emailResponce	= stripslashes(get_option('wpss_emailResponce'));
		if(!$emailResponce) $emailResponce = "Thanks for taking the [quiztitle],\r\nYou scored [score] and were routed to [routed].\r\nYour answers:\r\n[answers]\r\nDo not reply to this email address. This is an auto-generated message.";
		$questionsArray 	= get_option('wpss_questionsArray');
		for($n=0;$n<$numQuestions;$n++){
			$questionsArray[$n] = stripslashes($questionsArray[$n]);
		}
	

	}
	$wpss_url = WP_PLUGIN_URL . "/wordpress-simple-survey/";
?>


<script type="text/javascript">
jQuery(document).ready(function($){
	$(".wpss_info").tooltip({position: "center right", opacity: 1.0});
});
</script>

<div class="wrap">
	<h2>Wordpress Simple Survey - Setup</h2>

	<form name="wpss_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="wpss_hidden" value="Y">

		<div id="icon-tools" class="icon32"></div>
		<h2>Simple Survey Options:</h2>
		
	
		<div class="wpss_options">
		<p><strong>Quiz Title: </strong><input type="text" name="wpss_quizTitle" value="<?php echo stripslashes($quizTitle); ?>" size="40" />
			<img title="Example:<br />Investments Survey" class="wpss_info" src="<?php echo $wpss_url.'images/wpss_info.png';?>" /></p>
			
		<p><strong>Quiz Intro: </strong><input type="text" name="wpss_quizIntro" value="<?php echo stripslashes($quizIntro); ?>" size="80" />
		<img title="Example:<br />Fill out the questionaire to find out which investment portfolio is right for you." class="wpss_info" src="<?php echo $wpss_url.'images/wpss_info.png';?>" /></p>

		<p><strong>Quiz Submit Text: </strong><input type="text" name="wpss_quizOutro" value="<?php echo stripslashes($quizOutro); ?>" size="80" />
			<img title="Example:<br />Click submit and be taken to the investment style that is right for you." class="wpss_info" src="<?php echo $wpss_url.'images/wpss_info.png';?>" /></p>

		<p><strong>Number Of Questions: </strong><input type="text" name="wpss_numQuestions" value="<?php echo stripslashes($numQuestions); ?>" size="2" maxlength="2" /><input class="button-secondary" type="submit" name="Add Question" value="<?php _e('Update', 'wpss_trdom' ) ?>" /></p>

		<p><strong>Send Results to Email Address: </strong><input type="text" name="wpss_sendEmail" size="40" value="<?php echo stripslashes($sendEmail); ?>" />
			<img title="Leave blank to receive no emails. If email is entered, users will be required to enter their name and email." class="wpss_info" src="<?php echo $wpss_url.'images/wpss_info.png';?>" /></p>

		<p><strong>Auto-Respond to Users: </strong><input type="radio" name="wpss_auto" value="yes" <?php if($quizAuto == 'yes') echo 'checked';?> />Yes
		<input type="radio" name="wpss_auto" value="no" <?php if($quizAuto != 'yes') echo 'checked';?> />No
			&nbsp;<img title="Turning this on will send an email to quiz takers. It will also require users to input their name and email." class="wpss_info" src="<?php echo $wpss_url.'images/wpss_info.png';?>" /></p>
		<p><strong>Auto-Respond Email Content:</strong>&nbsp;<img title="Use:<br />[routed], [score], [name], [quiztitle], and [answers], for data." class="wpss_info" src="<?php echo $wpss_url.'images/wpss_info.png';?>" /></p>
		<p><textarea rows="8" cols="40" name="wpss_emailResponce"><?php echo stripslashes($emailResponce); ?></textarea></p>

		<p><strong>Store results: </strong><input type="radio" name="wpss_quizTrack" value="yes" <?php if($quizTrack == 'yes') echo 'checked';?> />Yes
		<input type="radio" name="wpss_quizTrack" value="no" <?php if($quizTrack != 'yes') echo 'checked';?> />No</p>
		<p style="margin:-10px 0 0 0;padding:0;width:50%;">Turning this on forces users to enter a name and email address and then stores a copy with the results in the database. The administrators can then see the results in the Wordpress backend under 'WPSS Option' -> 'WPSS Results'.
		</p>
		
		<p><strong>Mail From Name: </strong><input type="text" name="wpss_mailfrom" value="<?php echo stripslashes($mailfrom); ?>" size="40" />&nbsp;<img title="Attempt to use special mail from name.<br />Example:<br />Investment Quizer" class="wpss_info" src="<?php echo $wpss_url.'images/wpss_info.png';?>" /><br /><span style="font-size:10px">Will not work on many shared hosting platforms.</span></p>
		</div>

		<hr />
		<div id="icon-edit-pages" class="icon32"></div>
		<h2><?php _e("Questions: " ); ?></h2>
		<p>Enter questions delimited by the question, followed by the question weight in parentheses, then a set of ordered alphabetical characters associated with each possible answer, followed by the weight in parentheses for each choice (only paste from basic text editor): </p>


		<?php
		for($n=0;$n<$numQuestions;$n++){
		?>
		<p><h3><?php print("Question: " . ($n+1)); ?></h3><textarea rows="10" cols="100" name="wpss_questions_<?php echo $n;?>"><?php echo stripslashes($questionsArray[$n]); ?></textarea><br /></p>
		<?php
		}
		?>
		<p class="wpss_b">Example:</p>
		<p>Question 1:</p>
		<p>What is your age range? (3)<br />
		A. 18-25 (1)<br />
		B. 26-35 (2)<br />
		C. 36-45 (4)<br />
		D. 46-Above (5)</p>
		<p>Question 2:</p>
		<p>What is your pay range? (5)<br />
		A. $30,000-40,000 (1)<br />
		B. $40,000-60,000 (2)<br />
		C. $60,000-Above (4)<br /></p>
		<hr />

		<div id="icon-link-manager" class="icon32"></div>
		<h2><?php _e("Scoring Ranges and Routing: ");?></h2>
		<p>Enter inclusive ranges in parenthesis followed by the URL associated with the particular range in square brackets, following by a blank line.</p>

		<textarea rows="10" cols="100" name="wpss_ranges"><?php echo $ranges; ?></textarea>

		<p class="wpss_b">Example:</p>
		<p>(0-12) [http://example.com/investments/conservative/]<br /><br />(13-30) [http://example.com/investments/moderate/]<br /><br />(30-999) [http://example.com/investments/aggressive/]</p>


		<div class="wpss_options">
			<p class="wpss_b">Scoring:</p>
			<p class="wpss_large">&#8721;( QuestionWeight<sub>n</sub> * AnswerWeight<sub>n</sub> )</p>
			<p>Ex. Key: <br /> 1. C <br />2. B <br />  = (3 * 4) + (5 * 2) = 22</p>

			<p>Update settings and insert:<br /><br />[wp-simple-survey]<br /><br />into your content, where you want the quiz to appear.</p>
			<p class="submit">
			<input class="button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'wpss_trdom' ) ?>" />
			</p>
		</div>
	</form>
</div>
