=== Wordpress Simple Survey ===
Contributors: richard_steeleagency
Donate link: http://www.steele-agency.com/2010/08/wordpress-simple-survey/
Tags: survey, questionnaire, quiz, poll, exam, test
Requires at least: 3.1.1
Tested up to: 3.1.1
Stable tag: 2.0.0


A jQuery-based plugin that displays a weighted survey, and then routes user to location based on score. 

== Description ==

Wordpress Simple Survey is a plugin that allows for the creation of a survey, quiz, or questionnaire and the tracking of user submissions. Scores, Names, and Results can be recorded, emailed, and displayed in the WordPress backend. The plugin is jQuery based which allows users to seamlessly and in a graphically appealing manner, take the quiz without reloading the page. Each answer is given a weight (or score/points). Once a quiz is submitted, the user is taken to a predefined URL based on their score range; this page can be any URL including pages setup in WordPress that can contain information relevant to the particular scoring range, including the user's score and answer set. The plugin can also keep a record of all submissions and email results to a predefined email address. 


* [Project Homepage](http://www.steele-agency.com/2010/08/wordpress-simple-survey/)
* [Support](http://www.steele-agency.com/2010/08/wordpress-simple-survey/#comments)
* [Extended Version](http://www.steele-agency.com/2011/04/wordpress-simple-survey-extended/)

== Installation ==

1. Upload plugin to the 'wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Once activated the new menu item: WPSS Options, is created
4. Configure your options, make sure to select the number of questions you want and click 'Update'
5. Enter Questions, Answers, Weights, and Routes in the format specified, DO NOT PASTE FROM MS WORD, use a basic text editor

== Frequently Asked Questions ==

= Are the results tracked? =

The results, Name, and Email addresses are stored in your database, and display in the 'WPSS Results' menu.

= I don't want the user to be immediately directed to the end page, how can I create a buffer page? =

Simple, instead of linking a score range with the end page, link each range with a separate buffer page that explains their score(or whatever) and then have that page link to the end page.

= What type of quizzes can I create? =

Two obvious ways of using this plugin are to create a Survey-Type quiz that routes user to a location based on their input, another is to use the plugin as a Quiz-Type manager where users are routed to either a "Passing" or "Failing" page. Also note, that results are recorded along with the user's email address (if this option is selected), so for a Survey-Type quiz, an admin can follow up with the user (market to them based on responses to quiz), or an admin can administer a test and record who passed and who failed. 

= How do I make the quiz show up in my content? =

Add the string: [wp-simple-survey] to an article.

== Screenshots ==

1. Using quiz
2. Progress Bar
3. Submit Results
4. Email Results
5. Backend Quiz Management
6. Backend Results View
7. Diagram

== Changelog ==

= 2.0.0 =
* Rewrote plugin allowing for multiple quizzes, better storage of answers, custom fields, and much more

= 1.5.3 =
* Fixed Next button bug on submit slide click trigger

= 1.5.2 =
* Changed function name for more compatability

= 1.5.1 =
* Gave Admin function calls less generic names for more compatability
* Changed jQuery Tools import function on backend, to execute only when needed, for more capatibility

= 1.5.0 =
* Added Auto-Respond Functionality 
* Changed php::mail() function to wp_mail() function from WP API
* Modified Admin look and feel

= 1.4.1 =
* Improved CSS to reset spacing and padding on more themes

= 1.4 =
* Improved mail() function and admin CSS

= 1.3 =
* Fixed bug in function that registers WPSS Menus in backend.

= 1.2 =
* Improved import method for all javascript libraries. WPSS is now using WP native versions of jQuery & jQuery-UI core. These import in noConflict() mode which is taken advantage of by the plugin. This ensures fewer conflict with existing plugins and themes. Checkform JS method is also updated (by name only); it is now wpss_checkForm(form), this also reduces conflict with existing themes' and plugins' checkform methods. 

= 1.1 =
* Changed jQueryUI import method to ensure that only one copy is being registered

= 1.0 =
* Originating version.

== Upgrade Notice ==

= 1.4 =
Improved mail() function and admin CSS

= 1.3 =
Fixed bug in function that registers WPSS Menus in backend.

= 1.2 =
Improved import method for all javascript libraries. WPSS is now using WP native versions of jQuery & jQuery-UI core. These import in noConflict() mode which is taken advantage of by the plugin. This ensures fewer conflict with existing plugins and themes. Checkform JS method is also updated (by name only); it is now wpss_checkForm(form), this also reduces conflict with existing themes' and plugins' checkform methods. 

= 1.1 =
Changed jQueryUI import method to ensure that only one copy is being registered

= 1.0 =
None

== Markdown ==

Order:

1. Outputs a Quiz
2. Tracks User Submissions
3. Route user to location based on results

[Steele Agency]: http://www.steele-agency.com/2010/08/wordpress-simple-survey/
            "Plugin URL"
