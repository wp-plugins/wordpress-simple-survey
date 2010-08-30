<?php
	// Update Options
	if($_POST['wpss_hidden'] == 'Y') {
		
		//Form data sent, update database (adds slashes)
		$quizTitle	= htmlentities($_POST['wpss_quizTitle'],ENT_QUOTES);
		update_option('wpss_quizTitle', $quizTitle);

		$quizIntro	= htmlentities($_POST['wpss_quizIntro'],ENT_QUOTES);
		update_option('wpss_quizIntro', $quizIntro);

		$quizOutro	= htmlentities($_POST['wpss_quizOutro'],ENT_QUOTES);
		update_option('wpss_quizOutro', $quizOutro);
	
		$ranges		= htmlentities($_POST['wpss_ranges'],ENT_QUOTES);
		update_option('wpss_ranges', $ranges);

		$numQuestions	= htmlentities($_POST['wpss_numQuestions'],ENT_QUOTES);
		update_option('wpss_numQuestions', $numQuestions);	

		$quizTrack	= htmlentities($_POST['wpss_quizTrack'],ENT_QUOTES);
		update_option('wpss_quizTrack', $quizTrack);	

		$sendEmail	= htmlentities($_POST['wpss_sendEmail'],ENT_QUOTES);
		update_option('wpss_sendEmail', $sendEmail);	

		$questionsArray = array();

		for($n=0;$n<$numQuestions;$n++){
			$thisQuest = "wpss_questions_" . $n;
			$questionsArray[$n] = $_POST[$thisQuest];
		}
		update_option('wpss_questionsArray', $questionsArray);

		
		// Get value from database, remove slashes
		$ranges			= stripslashes(get_option('wpss_ranges'));
		$quizTitle		= stripslashes(get_option('wpss_quizTitle'));
		$quizIntro		= stripslashes(get_option('wpss_quizIntro'));
		$quizOutro		= stripslashes(get_option('wpss_quizOutro'));
		$numQuestions	= stripslashes(get_option('wpss_numQuestions'));
		$quizTrack		= stripslashes(get_option('wpss_quizTrack'));
		$sendEmail	= stripslashes(get_option('wpss_sendEmail'));
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
		$questionsArray 	= get_option('wpss_questionsArray');
		for($n=0;$n<$numQuestions;$n++){
			$questionsArray[$n] = stripslashes($questionsArray[$n]);
		}
	

	}


?>

<div class="wrap">
	<h2>Wordpress Simple Survey - Setup</h2>

	<form name="wpss_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<input type="hidden" name="wpss_hidden" value="Y">

		<div id="icon-tools" class="icon32"></div>
		<h2>Simple Survey Options:</h2>
		
	
		<div class="wpss_options">
		<p><?php _e("<strong>Quiz Title:</strong> " ); ?><input type="text" name="wpss_quizTitle" value="<?php echo stripslashes($quizTitle); ?>" size="40" /><br /><?php _e("Example: Investments Survey" ); ?></p>
		<p><?php _e("<strong>Quiz Intro:</strong> " ); ?><input type="text" name="wpss_quizIntro" value="<?php echo stripslashes($quizIntro); ?>" size="80" /><br /><?php _e("Example: Fill out the questionaire to find out which investment portfolio is right for you." ); ?></p>

		<p><?php _e("<strong>Quiz Submit Text:</strong> " ); ?><input type="text" name="wpss_quizOutro" value="<?php echo stripslashes($quizOutro); ?>" size="80" /><br /><?php _e("Example: Click submit and be taken to the investment style that is right for you." ); ?></p>

		<p><?php _e("<strong>Number Of Questions:</strong> " ); ?><input type="text" name="wpss_numQuestions" value="<?php echo stripslashes($numQuestions); ?>" size="2" maxlength="2" /><input class="button-secondary" type="submit" name="Add Question" value="<?php _e('Update', 'wpss_trdom' ) ?>" /></p>

		<p><?php _e("<strong>Send Results to Email Address:</strong> " ); ?><input type="text" name="wpss_sendEmail" size="40" value="<?php echo stripslashes($sendEmail); ?>" /><br />Leave blank to receive no email of results.</p>

		<p><?php _e("<strong>Track results:</strong> " ); ?><input type="radio" name="wpss_quizTrack" value="yes" <?php if($quizTrack == 'yes') echo 'checked';?> />Yes
		<input type="radio" name="wpss_quizTrack" value="no" <?php if($quizTrack != 'yes') echo 'checked';?> />No</p>
		<p style="margin:-10px 0 0 0;padding:0;width:50%;">Turning this on forces users to enter a name and email address and then stores a copy with the results in the database. The administrators can then see the results in the Wordpress backend under 'WPSS Option' -> 'WPSS Results'.
		</p>
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

			<p>Update settings and insert: <strong>[wp-simple-survey]</strong> into your content, where you want the quiz to appear.</p>
			<p class="submit">
			<input class="button-primary" type="submit" name="Submit" value="<?php _e('Update Options', 'wpss_trdom' ) ?>" />
			</p>
		</div>
	</form>
</div>
