$(document).ready(function(){
	$("#login_button").bind('click', function(){
		//ajaxReq("login", "html", ); //TODO
	});
})

function ajaxReq(String action, String format, String reqparams) {
	var respunse;
	$(document).ready(function(){
		$.ajax({
			url: "ajax.php?action="+action,
			data: reqparams,
			dataType: format,
			type: "POST"
		});
	})
}