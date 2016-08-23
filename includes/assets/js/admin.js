jQuery(document).ready(function($){

	$('.clone_statuses').on('click',function(){
		var parent = $(this).parent();
		var cloned = parent.find('.statuses_dom').clone();
		$(cloned).find('input').attr('name',cloned.find('input').attr('rel-name'));
		parent.find('ul.status_list').append('<li>'+cloned.html()+'</li>');
	});

});