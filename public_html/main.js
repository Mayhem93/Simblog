$(document).ready(function(){
	$("#login_button").on("click", adminLogin);
	$("#admin_logout").on("click", adminLogout);
	$("body").on("keypress", function(event) {
		if($('[name="adminPassword"]:focus').length && event.which == 13)
			adminLogin();
	});

	$("img.post-delete").each(function(i,item){
		var id = $(this).parents(".post-outer").attr("id").slice(5);
		$(this).on("click", function(){ deletePost(id);});
	});
	
	$("#submitComment").on("click", function(){
		var id = window.location.search.match(/id=\d+/)[0].slice(3);
		
		addComment(id);
	});
	
});

function adminLogout() {
	var callbacks = {
			success: function(data, textStatus, jqHXR) {
				//$("#admin_box")
				$("#admin_box").html(data);
				$("#login_button").on("click", adminLogin);
				$("img.post-delete").remove();
			},
			error: null,
			complete: null
	};
	ajaxReq("logout", "html", undefined, callbacks);
}

function deletePost(id) {
	var callbacks = {
			success: function(data) {
				if(data == "1")
					$("div#post_"+id).animate({
						height: 0,
						opacity: 0
					}, {
						duration: 1000,
						complete: function(){$(this).remove();}
					});
				else
					this.error(null, "Error", null);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert(textStatus);
			},
			complete: null
	};
	
	ajaxReq("deletePost", undefined, "id="+id, callbacks);
}

function adminLogin() {
	var username = document.admin_login.adminUsername.value;
	var password = document.admin_login.adminPassword.value;
	var callbacks = {
			success: function(data, textStatus, jqXHR) {
				$("#admin_box").html(data);
				$("#admin_logout").on("click", adminLogout);
				addDeleteEvents();
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

function addComment(post_id) {
	var callbacks = {
			success: function(data, textStatus, jqXHR) {
				window.location.reload(true);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert(textStatus);
			},
			complete: null
	};
	
	ajaxReq("addComment", "text", "post_id="+post_id+"&"+$("#commentForm").serialize(), callbacks);
}

function addDeleteEvents() {
	$('[id^="post"]').each(function(i,item){
		var deleteImg = document.createElement("img");
		deleteImg.setAttribute("class", "post-delete");
		deleteImg.setAttribute("src", "img/close-button.png");
		deleteImg.setAttribute("alt", "Delete");
		
		var id = item.attributes.getNamedItem("id").value.slice(5);
		
		$("#post_"+id+" h2.entry-title").after(deleteImg);
		$(deleteImg).on("click", function(){
			deletePost(id);
		});
	});
}

function ajaxReq(action, format, reqparams, callbacks) {
	$.ajax({
		url: "ajax.php?action="+action,
		data: reqparams,
		dataType: format,
		type: "POST",
		success: callbacks.success,
		error: callbacks.error,
		complete: callbacks.complete
	});
}