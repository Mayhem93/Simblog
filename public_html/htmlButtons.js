$(document).ready(function(){
	$('#dialogMessage').dialog({
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
	var abbrForm = '<label for="abbr">Abbreviation</label><input id="abbr" type="text">\
		<label for="abbrText">Abbreviation Meaning</label><input id="abbrText" type="text">';
	
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
});

function assessInsertMethod(tag, title, htmlContent, extraAttr) {
	var textarea = document.addPost.post_content;
	
	if(textarea.selectionStart == textarea.selectionEnd)
		insertDialogMessage(tag, title, htmlContent, extraAttr);
	else
		insertDirectly(tag, extraAttr);
}

function insertDialogMessage(tag, title, htmlContent, extraAttr) {
	var dialogMessage = $("#dialogMessage");
	
	var options = {
			title: title,
			tag: tag,
			attr: (extraAttr == undefined) ? "" : extraAttr
	};

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
		getTagWithText(tag, getTextAreaSelection(textarea), extraAttr);
	}
}

function okFunction() {
	var textarea = document.addPost.post_content;
	
	var attr = $("#dialogMessage").dialog("option", "attr");
	var tag = $("#dialogMessage").dialog("option", "tag");
	
	console.log(attr);
	
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
				textarea.value.substr(textarea.selectionStart);
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
	return "<"+tag+extraAttr+">"+text+"</"+tag+">";
}

function getTextAreaSelection(textarea) {
	return textarea.value.substr(textarea.selectionStart, textarea.selectionEnd-textarea.selectionStart);
}  

function isLastCharacter(textarea) {
	return textarea.value.length == textarea.selectionStart;
}
