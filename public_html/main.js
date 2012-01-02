$(document).ready(function(){
	if(document.getElementById("login_button") != undefined)
		bind_login_button();
	else if(document.getElementById("admin_logout"))
		bind_logout();
	if(document.admin_login != undefined)
		document.admin_login.addEventListener("submit",function(){return false;},false);
	$("body").bind("keypress", function(event){
		if($('[name="adminPassword"]:focus').length && event.which == 13)
			sendLogin();
	});
});

function bind_login_button() {
	$("#login_button").bind("click",sendLogin);
}

function bind_logout() {
	$("#admin_logout").bind('click', function(){
		var callbacks = {
				success: function(data, textStatus, jqHXR) {
					$("#admin_box").html(data);
					bind_login_button();
				},
				error: null,
				complete: null
		};
		ajaxReq("logout", "html", undefined, callbacks);
	});
}

function sendLogin() {
	var username = document.admin_login.adminUsername.value;
	var password = document.admin_login.adminPassword.value;
	var callbacks = {
			success: function(data, textStatus, jqXHR) {
				$("#admin_box").html(data);
				bind_logout();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				if(errorThrown == "Unauthorized") {
					if(!$(".login_fail").length)
						$("#login_button").after('<p class="login_fail">Login failed!</p>');
				}
			},
			complete: null
	};
	ajaxReq("login", "html", "username="+username+"&password="+password, callbacks);
}

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