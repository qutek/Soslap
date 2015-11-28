(function($){
	/**
	 * Ajax on register
	 * @param  {[type]} e) {		e.preventDefault();	   		   	var reg_nonce [description]
	 * @return {[type]}    [description]
	 */
	$(document).on('submit', '#hackgov_register',function(e) {
		e.preventDefault();

	   	/**
	    * AJAX URL where to send data
	    * (from localize_script)
	    */
	   	var reg_nonce = $('#hackgov_new_user_nonce').val();
	   	var form  = $('#hackgov_register');

	   	var submit_text = form.find('input[type="submit"]').val();
	   	form.find('input[type="submit"]').val('Mohon tunggu');

	   	// Data to send
	   	data = {
	      action: 'register_user',
	      nonce: reg_nonce,
	      email: form.find('#reg_email').val(),
	      pass: form.find('#reg_password').val(),
	   	};

	   	// Do AJAX request
	   	$.post(hackgov_obj.ajax_url, data, function(response) {
	   		
	   		form.find('input[type="submit"]').val(submit_text);

         	if (response === 'success') {
	            // If user is created
	            $('#hackgov-messages').addClass('success').html('Pendaftaran berhasil dilakukan.').fadeIn();

	            // redirect user if isset
	            if (hackgov_obj.redirect_url){
	            	// alert('woy '+hackgov_obj.redirect_url);
					window.location.href = hackgov_obj.redirect_url;
					return;
	            }
	            
	            // reset form
	            form.reset();

         	} else {
	            $('#hackgov-messages').addClass('error').html(response).fadeIn();
         	}
	   	});

	});
})(jQuery);