

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
	
	$('[id^=comment_]').each(function(i, item){
		$(item).find(".comment-delete").on("click", function(){
			deleteComment(item.id.slice(8));
		});
	});
	
});

function adminLogout() {
	var callbacks = {
			success: function(data, textStatus, jqHXR) {
				$("#admin_box").html(data);
				$("#login_button").on("click", adminLogin);
				$(".admin_buttons").remove();
				$("img.comment-delete").remove();
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
				addAdminButtons();
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
				$(".commentlist").prepend(data);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert(textStatus);
			},
			complete: null
	};
	
	ajaxReq("addComment", undefined, "post_id="+post_id+"&"+$("#commentForm").serialize(), callbacks);
}

function deleteComment(comment_id) {
	var callbacks = {
			success: function(data, textStatus, jqXHR) {
				$("#comment_"+comment_id).animate({
					height: 0,
					opacity: 0
				}, {
					duration: 1000,
					complete: function(){$(this).remove();}
				});
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert(textStatus);
			},
			complete: null
	};
	
	ajaxReq("deleteComment", undefined, "cid="+comment_id, callbacks);
}

function addAdminButtons() {
	$('[id^="post_"]').each(function(i,item){
		var id = item.attributes.getNamedItem("id").value.slice(5);
		
		var modify_anchor = document.createElement("a");			//link to modifyPost
		modify_anchor.setAttribute("href", "?action=modifyPost&id="+id);
		
		var modifyImg = document.createElement("img");				//the link is an image
		modifyImg.setAttribute("class", "post-modify");
		modifyImg.setAttribute("src", "img/edit.png");
		modifyImg.setAttribute("alt", "Modify");
		
		var deleteImg = document.createElement("img");				//delete post image
		deleteImg.setAttribute("class", "post-delete");
		deleteImg.setAttribute("src", "img/close-button.png");
		deleteImg.setAttribute("alt", "Delete");
		
		var post_element = $("#post_"+id+" h2.entry-title");		//the position where to insert
		var admin_btns_div = document.createElement("div");			//buttons will be inside a div
		admin_btns_div.setAttribute("class", "admin_buttons");
		
		modify_anchor.appendChild(modifyImg);
		admin_btns_div.appendChild(deleteImg);						//then the delete img
		admin_btns_div.appendChild(modify_anchor);					//first appears the modify link
		post_element.after(admin_btns_div);							//insert the div in the document
		
		$(deleteImg).on("click", function(){						//attaching events to make it work
			deletePost(id);
		});
	});
	
	$('[id^=comment_]').each(function(i, item){
		var deleteImg = document.createElement("img");
		deleteImg.setAttribute("class", "comment-delete");
		deleteImg.setAttribute("id", "delete_comment_"+item.id.slice(8));
		deleteImg.setAttribute("src", "img/delete-button.png");
		deleteImg.setAttribute("alt", "Delete Comment");
		
		$(item).find(".commentDate").before(deleteImg);
		$(deleteImg).on("click", function(){
			deleteComment(item.id.slice(8));
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