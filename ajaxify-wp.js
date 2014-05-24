
$(document).ready( function() {
	$("a").click( linkClick );
});

function loadingShow()
{
	
}

function loadingHide()
{
	
}

function linkClick(event) {
	if ( event.ctrlKey || event.button==1 ) return;
	
	var elm = $(event.target);
	var href = elm.attr('href');
	
	var hrefs = href.split('?');
	if ( hrefs[1] ) hrefs[1] += "&ajax";
	else hrefs[1] = "ajax";
	href = hrefs[0]+'?'+hrefs[1];
	
	$.ajax({
		type: "GET",
		url: href,
		//chache: false,
		success: function(html) {
			//alert(html);
			$("#content").slideUp( 'normal', function() {
				$("#content").html(html);
				$("#content a").click( linkClick );
				$("#content").slideDown( 'normal' );
			});
		}
	});
	event.preventDefault();
}
