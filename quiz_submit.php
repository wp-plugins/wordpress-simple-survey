<?php

if(isset($_POST['routing'])){
	
	// Email validation 
	function isValidEmail($email){
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}


	if($_POST['tracking'] == 'yes'){
		$error = '';
		
		// Validate Input
		if(!isValidEmail($_POST['email'])) $error.="Please insert a valid email address<br />";
		else{$email 	= htmlentities(stripslashes($_POST['email']));}
		$name 		= htmlentities(stripslashes($_POST['name']));
		$tracking	= true;
		if($error == ''){
			unset($_POST['email']);
			unset($_POST['name']);
		}
	}	

	$score = 0;
	$ranges;
	$routes;

	/* Header for Tracking */
	if($tracking){
		$message = '
		<h2>Quiz Results</h2>
		<p>
		Name: '.$name.'<br />
		Email: '.$email.'</p>
		';
	}

	/* Get The Score*/
	$questions = getQuestions();
	$n = stripslashes(get_option('wpss_numQuestions'));
	for($i=0;$i<$n;$i++){
		$score+=(str_replace( array('(',')'),'',$questions[2][$i][0][$_POST['group'.$i]+1])*$questions[3][$i]);
	}

	/* Find range that score lies in */
	$wpss_ranges = stripslashes(get_option('wpss_ranges'));
	// Grab scoring ranges
	preg_match_all("/\([\d]*\-[\d]*\)/",$wpss_ranges,$ranges);
	// Grab Routes
	preg_match_all('/\[(http:\/\/[^&"\'\s]+)\]/',$wpss_ranges,$routes);

	foreach($ranges[0] as $index => $range){
	
		$lowRange  = substr($range,1,stripos($range,'-')-1);
		$highRange = str_replace(")","",substr($range,stripos($range,'-')+1, strlen($range)-1));
		if($score>=$lowRange && $score<=$highRange){
			$location = str_replace(array('[',']'),"",$routes[0][$index]);
		}

	}

	// Add question and selected answer to message 
	if($tracking){
		$message .=	'<p>
					Score: '.$score.'<br />
					Routed to: '.$location.'</p>
					<h2>Answers:</h2>  
					';
		for($i=0;$i<$n;$i++){
			$message .= '<p>'.$questions[0][$i];
			$message .= $questions[1][$i][0][ $_POST['group'.$i] ] .'<br /></p>';
			
		}
	} 

	// Send email message 
	if(get_option('wpss_sendEmail')!=''){
		$from = 'WP Simple Survey <wpss@'.str_replace('http://','',get_bloginfo('url')).'>';
		$subject = 'New "'.stripslashes(get_option('wpss_quizTitle')).'" Submitted';
		// To send HTML mail, the Content-type header must be set
		$headers =	'MIME-Version: 1.0' . "\r\n" . 
					'Content-type: text/html; charset=iso-8859-1' . "\r\n" . 
					'From:' . $from . "\r\n" . 
					'Reply-To: ' . $from . "\r\n" . 
					'X-Mailer: PHP/' . phpversion();
		mail(stripslashes(get_option('wpss_sendEmail')), $subject, $message, $headers, '-f info@'.str_replace('http://','',get_bloginfo('url')));
	}

	// store results in database
	if($tracking){
		// Save message into Database
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpss_quizTracking';
		$rows_affected = $wpdb->insert( $table_name, array( 'results' => $message, 'time' => date('l jS F Y h:i:s A') ) );
		$wpdb->flush();
	}
	
	ob_start();
	header('Location: ' . $location); 
	ob_flush();
	exit; /* Make sure that code below does not get executed when we redirect. */
	break; // ensure stop execution

}

?>
