(function($){

	$('.user-menu-box a').click(function (e) {
		e.preventDefault()
		$(this).tab('show');
	});

	/**
	 * Ajax Vote up vote down
	 */
	$(document).on('click', '.hackgov-votes', function(e){
		e.preventDefault();

		var user_id = votes_obj.user_id;
		if(!user_id || user_id == 0){
			alert('Silahkan login dahulu.');
			document.location.href = votes_obj.login_url;
			return false;
		}

		var post_id = $(this).attr('data-id');
		var vote_type = $(this).attr('data-type');
		var count_el = $(this).closest('ul');

		var data = {
		      action: 'votes',
		      nonce: votes_obj.nonce,
		      post_id: post_id,
		      user_id: user_id,
		      type: vote_type
		   	};

		// Do AJAX request
	   	$.post(votes_obj.ajax_url, data, function(response) {
	   		// alert(response.status);
	   		console.log(response);
	   		if(response.status == 'success'){
	   			count_el.find('.voteup.hackgov-votes .vote-val').text(response.voteup_val);
	   			count_el.find('.votedown.hackgov-votes .vote-val').text(response.votedown_val);

	   			// update vote status
	   			if(response.is_voted_up == 1){
	   				count_el.find('.voteup.hackgov-votes').addClass('voted');
	   			} else {
	   				count_el.find('.voteup.hackgov-votes').removeClass('voted');
	   			}

	   			if(response.is_voted_down == 1){
	   				count_el.find('.votedown.hackgov-votes').addClass('voted');
	   			} else {
	   				count_el.find('.votedown.hackgov-votes').removeClass('voted');
	   			}

	   		} else {
	   			alert(response.message);
	   		}

	   	}, 'json');
	});

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