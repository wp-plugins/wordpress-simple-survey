<?php

function getQuiz(){

	// Returned Quiz HTML
	$retQuiz = '';

	$quizTitle	= stripslashes(get_option('wpss_quizTitle'));
	$quizIntro	= stripslashes(get_option('wpss_quizIntro'));
	$quizOutro	= stripslashes(get_option('wpss_quizOutro'));
	$questions	= stripslashes(get_option('wpss_questions'));
	$ranges		= stripslashes(get_option('wpss_ranges'));
	$formated_quiz 	= getQuestions();
	// prevent divide by zero on activation
	$numQuest = stripslashes(get_option('wpss_numQuestions'));
	if(!$numQuest){$numQuest=1;}

	$wpss_url = get_bloginfo('url') . '/?wpss-routing=results';

	$retQuiz .= '

	<script language="javascript" type="text/javascript">
	
	function wpss_checkform(form){

		if (form.name.value == "") {
			alert( "Please enter your name." );
			form.name.focus();
			return false ;
		}

		if (form.email.value == "") {
			alert( "Please enter a valid email address." );
			form.email.focus();
			return false ;
		}


		str = form.email.value;
		var at="@";
		var dot=".";
		var lat=str.indexOf(at);
		var lstr=str.length;
		var ldot=str.indexOf(dot);
		if (str.indexOf(at)==-1){
			alert("Please enter a valid email address.");
			return false;
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
			alert("Please enter a valid email address.");
			return false;
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
			alert("Please enter a valid email address.");
			return false;
		}

		if (str.indexOf(at,(lat+1))!=-1){
			alert("Please enter a valid email address.");
			return false;
		}

		if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
			alert("Please enter a valid email address.");
			return false;
		}

		if (str.indexOf(dot,(lat+2))==-1){
			alert("Please enter a valid email address.");
			return false;
		}

		if (str.indexOf(" ")!=-1){
			alert("Please enter a valid email address.");
			return false;
		}

		return true;
	}
	</script>

	<div id="wpss_survey">
	  <div class="form-container ui-helper-clearfix ui-corner-all">
		<h2>' . $quizTitle . ' </h2>
	    <div id="progress"><label id="amount">0%</label>
			<p class="pgress">Progress:</p></div>
			<form id="wpssform" name="wpssform" action="' . $wpss_url . '"  method="post" onsubmit="return wpss_checkform(this);">';?>

			<?php 
			for($i=0;$i<get_option('wpss_numQuestions');$i++){
				
				$retQuiz .= '<div id="panel' . ($i+1) . '" class="form-panel'; if($i>0){ $retQuiz .= ' ui-helper-hidden';} 
				$retQuiz .= '">';

				$retQuiz .= '
					<fieldset class="ui-corner-all">
	
						<p class="form_question">' . stripslashes($formated_quiz[0][$i]) . '</p>
						<div class="answer">';?>
						<?php 
						$n = 1;
						foreach ($formated_quiz[1][$i][0] as $index => $choice){

   
							$retQuiz .= '<input type="radio" name="group'.$i.'" value="'.$index.'" /><label>'.stripslashes($choice).'</label>';	 
							
							$n++;
						}

					$retQuiz .= '
						</div>
					</fieldset>

				</div>';
				 
			}


			$retQuiz .= '
			<div id="thanks" class="form-panel ui-helper-hidden">

				<fieldset class="ui-corner-all">

				<h3>'.$quizOutro.'</h3>
				<input type="hidden" name="routing" value="true" />		
				
				'.getUserInfo().'

				<input id="submitButton" type="submit" value="Calculate Results" />
				</fieldset>

			</div>
			<button id="next">Next &gt;</button><button id="back" disabled="disabled">&lt; Back</button>
		</form>
		</div>
	</div>
	';

	$retQuiz .= '
		<script type="text/javascript">

			// returns value selected from radio set
			function wpss_getCheckedValue(radioObj) {
				if(!radioObj)
					return "end";
				var radioLength = radioObj.length;
				if(radioLength == undefined)
					if(radioObj.checked)
						return radioObj.value;
					else
						return "";
				for(var i = 0; i < radioLength; i++) {
					if(radioObj[i].checked) {
						return radioObj[i].value;
					}
				}
				return "";
			}
		
			// current question number
			var wpss_curRadio = 0;
	
			(function($) {


				// run on load only
				($(this).attr("id") == "panel1") ? null : $("#next").attr("disabled", "disabled");
	
				//call progress bar constructor
				$("#progress").progressbar({ change: function() {
				
					//update amount label when value changes			
					$("#amount").text(Math.round($("#progress").progressbar("option", "value")) + "%");
				} });

			

				//set click handler for next button
				$("#next").click(function(e) {

					//stop form submission
					e.preventDefault();
					$("#next").removeAttr("disabled");
					$("#back").removeAttr("disabled");

					// increment cur question count
					wpss_curRadio++;
				
					//look at each panel
					$(".form-panel").each(function() {
					  
						//if it is not the first panel enable the back button
						//($(this).attr("id") != "panel1") ? null : $("#back").attr("disabled", "");
						

									
						//if the panel is visible fade it out
						($(this).hasClass("ui-helper-hidden")) ? null : $(this).fadeOut("fast", function() {
						  
							//add hidden class and show the next panel
							$(this).addClass("ui-helper-hidden").next().fadeIn("fast", function() {
							  
								//if it is the last panel disable the next button
    							($(this).attr("id") != "thanks") ? null : $("#next").attr("disabled", "disabled");	
								
								//remove hidden class from new panel
								$(this).removeClass("ui-helper-hidden");
								
								//update progress bar
								$("#progress").progressbar("option", "value", $("#progress").progressbar("option", "value") + '.(100/$numQuest).');
							});
						});
					});
				});			
				
				//set click handler for back button
				$("#back").click(function(e) {
				  
					//stop form submission
					e.preventDefault();

					// decrement cur question count
					wpss_curRadio-=1;

					//look at each panel
				  $(".form-panel").each(function() {
					  					
					  	//if it is not the last panel enable the next button
						//$("#next").removeAttr("disabled");
						//($(this).attr("id") != "thanks") ? null : $("#next").attr("disabled", "");
					  
						//if the panel is visible fade it out
					  	($(this).hasClass("ui-helper-hidden")) ? null : $(this).fadeOut("fast", function() {
						  
							//add hidden class and show the next panel
							$(this).addClass("ui-helper-hidden").prev().fadeIn("fast", function() {
							
							  	//if it is the first panel disable the back button
								($(this).attr("id") != "panel1") ? null : $("#back").attr("disabled", "disabled");
										
								//remove hidden class from new panel
								$(this).removeClass("ui-helper-hidden");
								
								//update progress bar
								$("#progress").progressbar("option", "value", $("#progress").progressbar("option", "value") - '.(100/$numQuest).');
							});
						});
					});
				});

				// handles button activation on clicks
				$("#wpssform").click(function() {
				
					checkVal = wpss_getCheckedValue(document.wpssform.elements["group"+wpss_curRadio]);
					checkPrevVal = wpss_getCheckedValue(document.wpssform.elements["group"+(wpss_curRadio-1)]);
					if(checkVal != "" & checkVal != "end"){
				  		$("#next").removeAttr("disabled");
					}
					else{
						$("#next").attr("disabled", "disabled");
					}
					if(checkPrevVal != "" & checkPrevVal != "end"){
				  		$("#back").removeAttr("disabled");
					}
				});

					
			})(jQuery);


		</script>';
	

	return $retQuiz;

}

function getRanges(){

	$ranges_input = get_option('wpss_ranges');
	
	return $ranges_input;
}


/* Returns array(questions, answers, answer_weights) */
function getQuestions(){

	$questions_input = get_option('wpss_questionsArray');

	$buffer;

	$n = 0;
	if($questions_input){
		foreach ($questions_input as $quest) {
			// position of question mark in current question
			$mark = strpos($quest, "?");

			// Grab all weights and the remove parenthesis
			preg_match_all("/\([\d]*\)/",nl2br($quest),$answer_weights_array[$n]);
		
			// filter question to match html
			$this_question = nl2br(preg_replace("[\([\d]*\)]", "", $quest)) . '<br />';

			// Get Questions
			$questions_array[$n] = stripslashes(substr( $quest, 0 , ($mark+1))) . '<br />';	
	
			// Get Answers
			preg_match_all("/[A-Z]\. .*\<br\ \/\>/",stripslashes($this_question),$buffer);

			// Buffer used to allow "." in questions, alongside A. B. C. for answers
			foreach ($buffer[0] as $i => $value) {
				if(strrpos($value,'?')!=False){unset($buffer[0][$i]);}
			}

			$answers_array[$n] = $buffer;
		
			// Get Question Weights
			preg_match("[\([\d]*\)]", $quest, $matches);
			$question_weights_array[$n] = str_replace(array('(',')'),'',$matches[0]);
			$n++;		
		}
	}

	return array(&$questions_array, &$answers_array, &$answer_weights_array,&$question_weights_array);
}


function getUserInfo(){
	
	$infoForm;
	
	if(stripslashes(get_option('wpss_quizTrack'))=='yes' ||
	   stripslashes(get_option('wpss_sendEmail'))!=''){

		$infoForm.='

		<div class="infoForm">

		<label for="name">Name*:</label> <input type="text" name="name" /><br />
		<label for="email">Email*:</label> <input type="text" name="email" />

		<input type="hidden" name="sendemail" value="true">

		</div>

		';

	}

	return $infoForm;
}


?>
