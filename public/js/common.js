// preloader
$(window).on('load', function() {
	$('.loader-inner').fadeOut();
	$('.loader').delay(400).fadeOut('slow');
});


$(function() {

	// autoresize textarea
	$('#message-field').autoResize({
		extraSpace : 11
	});


	// perfect scrollbar
	// $('.post-list').perfectScrollbar();


	//Tabs in login form
	$('.login-form .tab-item').not(':first').hide();
	$('.login-form .tab').on('click', function() {
		$('.login-form .tab').removeClass('active').eq($(this).index()).addClass('active');
		$('.login-form .tab-item').hide().eq($(this).index()).fadeIn();
	}).eq(0).addClass('active');


	// open-close sidebar
	$('.js-toggle-btn').on('click', function() {
		var wWidth = $(window).width();
		if (wWidth <= '480') {
			$('.js-user-list').removeClass('show');
			$(this).removeClass('on');
		} else if (wWidth > '480' && wWidth <= '768') {
			$(this).toggleClass('on');
			$('.js-user-list').toggleClass('show');
			// $('.user-list-noscroll').toggleClass('show');
		}
		return false;
	});

	$('.js-toggle-btn--fixed').on('click', function() {
		$('.js-user-list').toggleClass('show');
		$('.js-toggle-btn').toggleClass('on');
		return false;
	});


	// open-close searchform
	$('.js-search-btn-post').on('click', function(){

		var searchField = $('.js-search-field-post');

		searchField.on('change', function(){
			if(searchField.value !== "") {
				$('.js-post-form').submit();
			}
		});

		if (searchField.hasClass('on')) {
			searchField.removeClass('on').fadeOut(200).blur();
			$('.search-btn--post-submit').fadeOut(200);
		} else {
			searchField.addClass('on').fadeIn(200).focus();
			$('.search-btn--post-submit').fadeIn(200);
		}

	});

	$('.js-search-field-post').on('blur', function(){
		$(this).fadeOut(200).removeClass('on');
		$('.search-btn--post-submit').fadeOut(200);
	});


	// open-close dropdown menu
	$('.js-dropdown-toggle').on('click', function() {
		$('.js-dropdown-menu').slideToggle(200);
		return false;
	});


	// Close drop down menu and searchform by clicking outside
	$(document).on('click', function(){
		$('.js-search-field-post').fadeOut().removeClass('on');
		$('.js-dropdown-menu').slideUp(200);
	});

	$(".js-search-btn-post").on('click', function(e){
		e.stopPropagation();
	});

	$('.js-search-field-post').on('click', function(e){
		e.stopPropagation();
	});


	//set check when click on message-item
	$('.messages__list').on('click', '.message', function() {
		var checkbox = $(this).find('input[type="checkbox"]');
			if(checkbox.is(':checked')) {
				checkbox.prop('checked', false);
				$(this).removeClass('checked');
				$('.footer-buttons').removeClass('show');
			} else {
				checkbox.prop('checked', true);
				$(this).addClass('checked');
				$('.footer-buttons').addClass('show');
			}
	});


	//open-close footer menu
	$('.footer-button-close').on('click', function() {
		$('.footer-buttons').removeClass('show');
	});


	//delete post when click to delete button
	$('.footer-button-delete, .js-dropdown-item-delete').on('click', function() {

		$('.message').each(function(){
			if( $(this).hasClass('checked') ) {
				$(this).remove();
			}
		});

		$('.footer-buttons').removeClass('show');
	});


	//clear history
	$('.js-dropdown-item-clear').on('click', function() {

		$('.message').each(function(){
			$(this).remove();
		});

		$('.footer-buttons').removeClass('show');
	});


	// Create and send new message
	function createPost() {
		var newPost = $('<li class="message"></li>').append($('.js-message').html());
		var text = $('#message-field').val().replace(/\n/g, '<br />');
		var date = new Date();
		var timeNow = date.getHours() + ':' + date.getMinutes();
		newPost.find('.message__text p').html(text);
		newPost.find('.message__time').html(timeNow);
		$('.messages__list').append(newPost.clone());
		$('#message-field').val('');
		var postList = $('.js-post-list');
		postList.animate({scrollTop:postList.prop('scrollHeight')}, 200);
		// postList.scrollTop(postList.prop('scrollHeight'));
	}


	//automatic scroll to last post
	$('.js-post-list').animate({scrollTop:$('.js-post-list').prop('scrollHeight')}, 200);


	// Sending message by clicking send-message-button
	$('.send-message-btn').on('click', function(e){
		e.preventDefault();
		createPost();
	});


	// Sending message by clicking Enter, new line Ctrl+Enter, new line after empty line Shift+Enter
	$('#message-field').keydown(function(e){
		if ((e.which == 13 && !e.ctrlKey) && (e.which == 13 && !e.shiftKey)) {
			createPost();
			return false;
		}
		if ((e.which == 13 && e.ctrlKey) || (e.which == 13 && e.shiftKey)) {
			$(this).val(function(i,val){
				return val + "\n";
			});
		}
	});

});

function checkForm() {
    var password = document.getElementById('reg-password').value;
    var confirm_password = document.getElementById('reg-password-confirm').value;

    if (password != confirm_password) {
        //что-то сделать
        alert('Passwords do not match');

        return false;
    }

    // $.post(
    //     '\\public\\login\\register',
    //     {
    //         reg_login: reg_login,
    //         reg_username: reg_username,
    //         reg_password: reg_password
    //     },
    //     function(respond){
    //         if(respond == true){
    //             set_warnings('Great!', 'You\'ve just registereg) Enjoy!');
    //             setTimeout(function(){ window.location = "/public/home.php"; }, 2500);
    //         }else{
    //             set_warnings('Oops!', 'Sorry, login is already occupied. Pick another one');
    //         }
    //     }
    // );
}
