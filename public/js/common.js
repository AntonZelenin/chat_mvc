$(function() {

$('.js-toggle-btn').on('click', function() {
	$(this).toggleClass('on');
	$('.js-user-list').toggleClass('user-list--narrow');
	$('.js-post-list').toggleClass('post-list--wide');
	// $('.main-menu').slideToggle();
	return false;
});

$(window).resize(function(){
	narrowMenu();
});

narrowMenu();

function narrowMenu() {
	if($(window).width() <= '768') {
		$('.js-user-list').addClass('user-list--narrow');
		$('.js-post-list').addClass('post-list--wide');
		$('.js-toggle-btn').removeClass('on');
	}
	else {
		$('.js-user-list').removeClass('user-list--narrow');
		$('.js-post-list').removeClass('post-list--wide');
		$('.js-toggle-btn').addClass('on');
		// 	$('.main-menu').removeAttr('style');
	}
}


$('.js-search-btn-post').on('click', function(){
	var searchField = $(this).prev('.js-search-field-post');
	searchField.on('change', function(){
		if(searchField.value !== "") {
			$('.js-post-form').submit();
		}
	});
	searchField.toggleClass('on').fadeToggle(200);
});







});
