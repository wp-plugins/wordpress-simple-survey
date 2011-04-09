(function($) { 
  $(function() {

    /* Tooltip for admin backend examples */
		$(".wpss_info").tooltip({position: "center right", opacity: 1.0});

    /* Sliding results for admin view of submissions */
    $("#quiz_summary_holder").accordion();
    
    
    $(".wpss_upgraderequired").click(function(e){
      e.preventDefault();
      alert("You must upgrade to WordPress Simple Survey-Extended to use this feature.");    
    });    
    
    
  });
})(jQuery);



tinyMCE.init({

  elements : "wpss_tinyedit", 
	theme : "advanced",
	mode: "exact",
	theme_advanced_toolbar_location : "top",
	theme_advanced_buttons1 : "bold,italic,underline,separator,justifyleft,justifycenter,justifyright,separator,bullist,numlist,link,unlink",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	height:"150px",
	width:"100%"
});
