$(document).ready( function() {
	
	// Hide all subfolders at startup
	$(".php-file-tree").find("UL").hide();
	
	// Expand/collapse on click
	$(".pft-directory A").click( function() {
		$(this).parent().find("UL:first").slideToggle("medium");
		var dir = $(this).closest( "LI" ).children().first().text();
		var i = 1;
		while($(this).parents().eq(i).closest( "LI" ).children().first().text() !== '')
		{
			dir = $(this).parents().eq(i).closest( "LI" ).children().first().text() + '/' + dir; 
			i += 2;
		}
		$("tbody").append(newtr);
		if( $(this).parent().attr('className') == "pft-directory" ) return false;
		return false;
	});

});