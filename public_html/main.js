$(document).ready(function(){
	$("#login_button").bind('click', function(){
		var username = document.getElementsByName("adminUsername")[0].value;
		var password = document.getElementsByName("adminPassword")[0].value;
		var callbacks = {
				success: function(data, textStatus, jqXHR) {
					$("#admin_box").html(data);
				},
				error: function(jqXHR, textStatus, errorThrown) {
					if(errorThrown == "Unauthorized") {
						$("#login_button").after('<p style="color: red; text-align: center">Login failed!</p>');
					}
				},
				complete: null
		};
		ajaxReq("login", "html", "username="+username+"&password="+password, callbacks); //TODO
	});
});

function ajaxReq(action, format, reqparams, callbacks) {
	$(document).ready(function(){
		$.ajax({
			url: "ajax.php?action="+action,
			data: reqparams,
			dataType: format,
			type: "POST",
			success: callbacks.success,
			error: callbacks.error,
			complete: callbacks.complete
		});
	});
}