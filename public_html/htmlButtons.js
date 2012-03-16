var dialogMessage = null;

$(document).ready(function(){
	dialogMessage = $('#dialogMessage');
	
	dialogMessage.dialog({
		modal: false,
		autoOpen: false,
		resizable: false,
		position: "center",
		width: 455,
		buttons: {
			Ok: okFunction,
			Cancel: cancelFunction
		}
	});
	
	var genericForm = '<input id="inputText" type="text">';
	var abbrForm = '<label for="abbr">Abbreviation: </label><input id="abbr" type="text">\
		<label for="abbrText">Abbreviation Meaning: </label><input id="abbrText" type="text">';
	var listForm = '<label for="item1">1. </label><input id="item1" type="text">';
	var anchorForm = '<label for="name">Link name: </label><input id="name" type="text">\
		<label for="link">Link: </label><input id="link" type="text">';
	var imgForm = '<label for="positionImg">Image position: </label><select id="positionImg">\
		<option name="defaultCenter">Default (center)</option>\
		<option name="floatLeft">Left float</option>\
		<option name="floatRight">Right float</option>\
		</select><br>\
		<label for="imgTitle">Title/name: </label><input id="imgTitle" type="text">\
		<label for="imgSrc">Image link: </label><input id="imgSrc" type="text">';
	
	$('#boldButton').on("click", function(){
		assessInsertMethod("strong", "Insert bolded text", genericForm);
	});
	
	$('#italicButton').on("click", function(){
		assessInsertMethod("em", "Insert italic text", genericForm);
	});
	
	 $('#underlineButton').on("click", function(){
		 assessInsertMethod("span", "Insert underlined text", genericForm ,' class="underLined"');
	});
	 
	 $('#strikeoutButton').on("click", function(){
		 assessInsertMethod("span", "Insert striked text", genericForm ,' class="strikeOut"');
	});
	 
	 $('#abbrButton').on("click", function(){
		 assessInsertMethod("abbr", "Insert abbreviation", abbrForm);
	});
	 
	 $('#centeredText').on("click", function(){
		 assessInsertMethod("p", "Insert centered text", genericForm, ' class="textCenter"');
	});
	 
	 $('#rightText').on("click", function(){
		 assessInsertMethod("p", "Insert right-aligned text", genericForm, ' class="textRight"');
	});
	 
	 $('#justifyText').on("click", function(){
		 assessInsertMethod("p", "Insert justified text", genericForm, ' class="textJustify"');
	});
	 
	 $('#orderedList').on("click", function(){
		 insertDialogMessage("ol", "Insert ordered list", listForm);
	});
	 
	 $('#unorderedList').on("click", function(){
		 insertDialogMessage("ul", "Insert unordered list", listForm);
	});
	 
	 $('#linkButton').on("click", function(){
		 insertDialogMessage("a", "Insert link", anchorForm);
	});
	
	$('#quoteButton').on("click", function(){
		assessInsertMethod("blockquote", "Insert block quote", genericForm);
	});
	
	$('#imageButton').on("click", function(){
		insertDialogMessage("img", "Insert image", imgForm);
	});
});

function assessInsertMethod(tag, title, htmlContent, extraAttr) {
	var textarea = document.addPost.post_content;
	
	if(textarea.selectionStart == textarea.selectionEnd)
		insertDialogMessage(tag, title, htmlContent, extraAttr);
	else
		insertDirectly(tag, extraAttr);
}

function insertDialogMessage(tag, title, htmlContent, extraAttr) {
	var options = {
			title: title,
			tag: tag,
			attr: (extraAttr == undefined) ? "" : extraAttr
	};
	
	switch(tag) {
		default: break;
		case "ol":
		case "ul": {
			dialogMessage.dialog("option", "buttons", [
			{
				text: "Ok",
				click: okFunction
			},
			{
				text: "Cancel",
				click: cancelFunction
			},
			{
				text: "Insert item",
				click: function(){
					var id = dialogMessage.children("input").length+1;
					dialogMessage.append('<label for="item1">'+id+'. </label><input id="item'+id+'" type="text">');
				}
			}]);
			break;
		}
	}

	dialogMessage.dialog("option", options);
	dialogMessage.html(htmlContent);
	dialogMessage.dialog("open");
}

function insertDirectly(tag, extraAttr) {
	var textarea = document.addPost.post_content;
	
	if(isLastCharacter(textarea))
		textarea.value += getTagWithText(tag, getTextAreaSelection(textarea), extraAttr);
	else {
		textarea.value = textarea.value.substr(0, textarea.selectionStart) + 
		getTagWithText(tag, getTextAreaSelection(textarea), extraAttr) + 
		textarea.value.substr(textarea.selectionEnd);
	}
}

function okFunction() {
	var textarea = document.addPost.post_content;
	
	var attr = dialogMessage.dialog("option", "attr");
	var tag = dialogMessage.dialog("option", "tag");
	
	switch(tag) {
		case "strong":
		case "span":
		case "p":
		case "em": {
			var valueText = $("#inputText").attr("value");
			
			if(isLastCharacter(textarea)) //just append the text
				textarea.value += getTagWithText(tag, valueText, attr);
			else {
				textarea.value = textarea.value.substr(0, textarea.selectionStart) + 
				getTagWithText(tag, valueText, attr) +
				textarea.value.substr(textarea.selectionEnd);
			}
			break;
		}
		case "abbr": {
			var abbreviation = $("#abbr").attr("value");
			var abbrevText = $("#abbrText").attr("value");
			
			if(isLastCharacter(textarea)) //just append the text
				textarea.value += getTagWithText(tag, abbreviation, ' title="'+abbrevText+'"');
			else {
				textarea.value = textarea.value.substr(0, textarea.selectionStart) + 
				getTagWithText(tag, abbreviation, ' title="'+abbrevText+'"') +
				textarea.value.substr(textarea.selectionStart);
			}
			break;
		}
		case "ol":
		case "ul": {
			
			var listString = "<"+tag+">\n";
			dialogMessage.children("input").each(function(index, element){
				listString += "\t<li>"+element.value+"</li>\n";
			});
			listString += "</"+tag+">\n";
			
			if(isLastCharacter(textarea)) //just append the text
				textarea.value += listString;
			else {
				textarea.value = textarea.value.substr(0, textarea.selectionStart) + 
				listString + textarea.value.substr(textarea.selectionStart);
			}
			break;
		}
		case "a": {
			var linkName = $("#name").attr("value");
			var linkHref = $("#link").attr("value");
			
			if(isLastCharacter(textarea)) //just append the text
				textarea.value += getTagWithText(tag, linkName, ' href="'+linkHref+'"');
			else {
				textarea.value = textarea.value.substr(0, textarea.selectionStart) + 
				getTagWithText(tag, linkName, ' href="'+linkHref+'"') +
				textarea.value.substr(textarea.selectionStart);
			}
			break;
		}
		case "img": {
			var position = $("#positionImg option:selected").attr("name");
			var imgTitle = $("#imgTitle").attr("value");
			var imgSrc = $("#imgSrc").attr("value");
			
			if(isLastCharacter(textarea)) //just append the text
				textarea.value += getTagWithText(tag, "", ' class="'+position+'" src="'+imgSrc+'" title="'+imgTitle+'"');
			else {
				textarea.value = textarea.value.substr(0, textarea.selectionStart) + 
				getTagWithText(tag, "", ' class="'+position+'" src="'+imgSrc+'" title="'+imgTitle+'"') +
				textarea.value.substr(textarea.selectionStart);
			}
			break;
		}
	}
		
	$(this).dialog("close");
	$(this).html("");
}

function cancelFunction() {
	$(this).dialog("close");
	$(this).html("");
}

function getTagWithText(tag, text, extraAttr) {
	if(!extraAttr)
		extraAttr = "";
	if(tag == "img")
		return "<"+tag+extraAttr+">";
	return "<"+tag+extraAttr+">"+text+"</"+tag+">";
}

function getTextAreaSelection(textarea) {
	return textarea.value.substr(textarea.selectionStart, textarea.selectionEnd-textarea.selectionStart);
}  

function isLastCharacter(textarea) {
	return textarea.value.length == textarea.selectionStart;
}
