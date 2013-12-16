	$(document).ready(function() {
	    // Hide all subfolders at startup
	    $(".php-file-tree").find("UL").hide();

	    // var thisdir = "./public/audiodata/";
	    // console.log($("#curdir").html());
	    // Expand/collapse on click
	    $(".pft-directory A").click(function() {
	        $(this).parent().find("UL:first").slideToggle("medium");
	        var dir = $(this).closest("LI").children().first().text();
	        // var thisdir = "./public/audiodata/";
	        var thisdir = $("#curdir").text();
	        var i = 1;
	        while ($(this).parents().eq(i).closest("LI").children().first().text() !== '') {
	            dir = $(this).parents().eq(i).closest("LI").children().first().text() + '/' + dir;
	            i += 2;
	        }
	        $("input[name=title]").val(thisdir + dir);
	        if ($(this).parent().attr('className') == "pft-directory") return false;
	        return false;
	    });

	    $('.php-file-tree').css({
	        'display': 'block',
	        'height': '2a50px',
	        'background-color': 'rgb(176, 224, 230)',
	        'overflow': 'scroll'
	    });
	});
