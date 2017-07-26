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


// open-close sidebar
$('.js-toggle-btn').on('click', function() {
	if ($(window).width() <= '480') {
		$('.js-user-list').removeClass('show');
		$(this).removeClass('on');
	} else {
		$(this).toggleClass('on');
		$('.js-user-list').toggleClass('user-list--narrow');
	}
	return false;
});

$('.js-toggle-btn--fixed').on('click', function() {
	$('.js-user-list').toggleClass('show');
	$('.js-toggle-btn').toggleClass('on');
	return false;
});

$(window).resize(function(){
	narrowMenu();
});

narrowMenu();

function narrowMenu() {
	if ($(window).width() <= '480') {
		$('.js-user-list').removeClass('user-list--narrow show');
	} 
	else if ($(window).width() > '480' && $(window).width() <= '768'  ) {
		$('.js-user-list').addClass('user-list--narrow');
		$('.js-toggle-btn').removeClass('on');
	}
	else {
		$('.js-user-list').removeClass('user-list--narrow');
		$('.js-toggle-btn').addClass('on');
	}
}


// open-close searchform
$('.js-search-btn-post').on('click', function(){
	var searchField = $(this).prev('.js-search-field-post');
	searchField.on('change', function(){
		if(searchField.value !== "") {
			$('.js-post-form').submit();
		}
	});
	searchField.toggleClass('on').fadeToggle(200);
});


// open-close dropdown menu
$('.js-dropdown-toggle').on('click', function() {
	$('.js-dropdown-menu').slideToggle(200);
	return false;
});

$('.js-dropdown-menu-item').on('click', function() {
	$('.js-dropdown-menu').slideUp(200);
	return false;
});


//set check when click on message-item
$('.message').on('click', function() {
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
$('.footer-button-delete').on('click', function() {

	$('.message').each(function(){
		if( $(this).hasClass('checked') ) {
			$(this).remove();
		}
	});

	$('.footer-buttons').removeClass('show');
});


});
