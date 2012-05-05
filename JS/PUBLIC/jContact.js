
$.ajaxSetup ({  
    cache: false  
});

function validateContact(){
    
    $('#formE').html('PROCESSING...');
    var name 		= $('#f_name').val();
    var email 		= $('#f_email').val();
    var subject		= $('#f_subject').val();
    var message		= $('#f_message').val();
    
    if(email == ""){
    	//need some kind of error in here?
    	return false;
    }

    var processURL = cur_url+"AJAX/contact/";
    $.post(processURL, { f_name: name, f_email: email, f_subject: subject, f_message: message, f_id: id, f_action: "contact-form" },
  		function(data) {
/*      		alert(data); */
    		if(data == "SUCCESS"){
			    $('#form').html(' ');
   			    $('#formE').html(' ');
    			$('#contactForm').html('<p>Got it! Thanks for contacting us!</p>');
    		} 
		else if(data == "ERROR") $('#formE').html('There was an error processing your form. Please verify all the fields are correct.');
    		else if(data == "BOT") $('#contactForm').html('<p>We processed your request.</p>');   		
  		});
    
    return false;
};