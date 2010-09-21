<?php
// quiz submitted
if(isset($_POST['routing'])){
	
   /* 
	* Get Options From Site Administrator
	*/
	// Get Send-To email and tracking bool
	$quizTitle	= stripslashes(get_option('wpss_quizTitle'));
	$track 	= stripslashes(get_option('wpss_quizTrack'));
	if($track == 'yes') $track = true;	//ensure boolean
	else $track = false;
	$sendTo = stripslashes(get_option('wpss_sendEmail'));
	if($_POST['sendemail'] == 'true' && $sendTo != '') $send = true;
	// auto-reply boolean
	$quizAuto = get_option('wpss_auto');
	if($quizAuto == 'yes') $quizAuto = true;	//ensure boolean
	else $quizAuto = false;
	// reply email
	$mailfromname	= stripslashes(get_option('wpss_mailfrom'));
	$mailfromaddr	= strtolower(str_replace(' ','',$mailfromname));
	$emailResponce	= stripslashes(get_option('wpss_emailResponce'));
	
	// Email validation 
	function isValidEmail($email){
		return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
	}
	// Get/Validate Input
	$email 	= htmlentities(stripslashes($_POST['email']));
	$name 	= htmlentities(stripslashes($_POST['name']));	

   /* 
   	*	Get The Score and Route
   	*/
	$score = 0; $ranges; $routes;	// scope placeholders
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
	
	
   /*
	*	Create output HTML and place in 
	* 	Tracking DB, Admin Email, and Auto-Respond Email
	* 	as requested in WPSS -> Options
	*/

	// From Header 
	$fromHeader = '	<h2>Quiz Results</h2>
					<p>
						Name: '.$name.'<br />
						Email: '.$email.'
					</p>
					';
	// Score, Route, questions and selected answer
	$scoreRoute ='<p>
					Score: '.$score.'<br />
					Routed to: '.$location.'
				 </p>
				 <h2>Answers:</h2>  
				';
	$userQA;
	for($i=0;$i<$n;$i++){
		$userQA .= '<p>'.$questions[0][$i];
		$userQA .= $questions[1][$i][0][ $_POST['group'.$i] ] .'<br /></p>';
	}

	// To send HTML mail, the Content-type header must be set
	$headers =	'MIME-Version: 1.0' . "\r\n" . 
				'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	if($mailfromname != '') {
		$from = "$mailfromname <".$mailfromaddr."@".str_replace('http://','',get_bloginfo('url')).">";

		$headers .=	'From:' . $from . "\r\n" . 
					'Reply-To: ' . $from . "\r\n";
	}
	$headers .=		'X-Mailer: PHP/' . phpversion();


   /*
   	*	Send Emails to Admin, User
   	*	Store info in DB 
   	*	as requested in options
   	*/

	// Send email message to admin, if requested
	if($send){
		// set admin email title and message
		$subject = "New '$quizTitle' Submitted";
		$message = $fromHeader . $scoreRoute . $userQA;
		wp_mail($sendTo, $subject, $message, $headers);
	}
	
	// Send email message to user, if requested
	if($quizAuto){
		$subject = "Thanks for taking the ".$quizTitle;
		// replace tag strings with user's data
		$resp = str_replace('[routed]',$location,$emailResponce);
		$resp = str_replace('[score]',$score,$resp);
		$resp = str_replace('[name]',$name,$resp);
		$resp = str_replace('[quiztitle]',$quizTitle,$resp);
		$resp = str_replace('[answers]',$userQA,$resp);
		// send email to user, if inputted valid email
		if(isValidEmail($email)) wp_mail($email, $subject, $resp, $headers);
	}

	// store results in database, if requested
	if($track){
		// Save message into Database
		global $wpdb;
		$table_name = $wpdb->prefix . 'wpss_quizTracking';
		$message = $fromHeader . $scoreRoute . $userQA;
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
