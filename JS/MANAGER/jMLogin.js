

function validateMLogin(){
    
    $('#formE').html('PROCESSING...');
    var user 		= $('#f_user').val();
    var pass 		= $('#f_pass').val();
    if(pass == ""){
    	return false;
    }

    var processURL = cur_url+"manager/AJAX/login//";
    $.post(processURL, { f_user: user, f_pass: pass, f_action: "contact-form" },
  		function(data) {
/*      		alert(data); */
    		if(data == "SUCCESS"){
			linkSPage("manager/overview/");
    		} 
		else if(data == "ERROR") $('#formE').html('There was an error processing your form. Please verify all the fields are correct.');
    		else if(data == "BOT") $('#contactForm').html('<p>We processed your request.</p>');   		
  		});
    
    return false;
}