jQuery(document).ready(function($){

	$('.clone_statuses').on('click',function(){
		var parent = $(this).parent();
		var cloned = parent.find('.statuses_dom').clone();
		$(cloned).find('input').attr('name',cloned.find('input').attr('rel-name'));
		parent.find('ul.status_list').append('<li>'+cloned.html()+'</li>');
	});

	$('.clone_announcements').on('click',function(event){
		event.preventDefault();
		var clone = $('.announcement_dom').html();
		$('.announcement_list').append('<li>'+clone+'</li>');
		$('.announcement_list').find('textarea,select').each(function(){
			var rel = $(this).attr('rel-name');
			if(typeof rel != 'undefined'){
				$(this).attr('name',rel);
			}
		});
	});

	$('body').on('click','.remove_link',function(){$(this).parent().remove();});
});